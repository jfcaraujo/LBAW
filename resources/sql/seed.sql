DROP TABLE IF EXISTS assigned CASCADE;
DROP TABLE IF EXISTS member CASCADE;
DROP TABLE IF EXISTS team CASCADE;
DROP TABLE IF EXISTS forum_comment CASCADE;
DROP TABLE IF EXISTS forum_question CASCADE;
DROP TABLE IF EXISTS comment CASCADE;
DROP TABLE IF EXISTS task CASCADE;
DROP TABLE IF EXISTS tasks_list CASCADE;
DROP TABLE IF EXISTS project CASCADE;
DROP TABLE IF EXISTS profile CASCADE;
DROP TABLE IF EXISTS team_member CASCADE;

DROP FUNCTION IF EXISTS verify_user_question_create() CASCADE; 
DROP FUNCTION IF EXISTS verify_user_answer_create() CASCADE; 
DROP FUNCTION IF EXISTS verify_user_assigned_create() CASCADE; 
DROP FUNCTION IF EXISTS verify_user_team_member_create() CASCADE; 
DROP FUNCTION IF EXISTS verify_user_task_update() CASCADE; 
DROP FUNCTION IF EXISTS update_after_user_removed() CASCADE; 

DROP TRIGGER IF EXISTS verify_user_question_create_trigger ON forum_question;
DROP TRIGGER IF EXISTS verify_user_answer_create_trigger ON forum_comment;
DROP TRIGGER IF EXISTS verify_user_assigned_create_trigger ON assigned;
DROP TRIGGER IF EXISTS verify_user_team_member_create_trigger ON team_member;
DROP TRIGGER IF EXISTS verify_user_task_update_trigger ON task;
DROP TRIGGER IF EXISTS update_after_user_removed_trigger ON member;

CREATE TABLE profile (
    id SERIAL PRIMARY KEY,
    username text NOT NULL CONSTRAINT username_uk UNIQUE,
    email text NOT NULL CONSTRAINT user_email_uk UNIQUE,
    name text NOT NULL,
    password text NOT NULL,
    image text DEFAULT 'EmptyUserPicture.jpg' NOT NULL
);

CREATE TABLE project (
    id SERIAL PRIMARY KEY,
    name text NOT NULL,
    description text,
	creator INTEGER REFERENCES profile (id) ON UPDATE CASCADE ON DELETE SET NULL,
    created TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL,
    image text DEFAULT 'EmptyProject.png' NOT NULL
);

CREATE TABLE tasks_list (
    id SERIAL PRIMARY KEY,
    name TEXT NOT NULL,
    id_project INTEGER NOT NULL REFERENCES project (id) ON UPDATE CASCADE ON DELETE CASCADE,
	creator INTEGER REFERENCES profile (id) ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE task (
	id SERIAL PRIMARY KEY,
	name TEXT NOT NULL,
	description TEXT,
	id_list INTEGER NOT NULL REFERENCES tasks_list (id) ON UPDATE CASCADE ON DELETE CASCADE,
	creator INTEGER REFERENCES profile (id) ON UPDATE CASCADE ON DELETE SET NULL,
	created TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL,
	solver INTEGER REFERENCES profile (id) ON UPDATE CASCADE ON DELETE SET NULL,
	solved TIMESTAMP WITH TIME zone,
	category TEXT NOT NULL,
	CONSTRAINT chk_date CHECK (solved > created)
);

CREATE TABLE comment (
	id SERIAL PRIMARY KEY,
	text TEXT NOT NULL,
	id_task INTEGER NOT NULL REFERENCES task (id) ON UPDATE CASCADE ON DELETE CASCADE,
	author INTEGER REFERENCES profile (id) ON UPDATE CASCADE ON DELETE SET NULL,
	created TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL	
);

CREATE TABLE forum_question (
	id SERIAL PRIMARY KEY,
	text TEXT NOT NULL,
    id_project INTEGER NOT NULL REFERENCES project (id) ON UPDATE CASCADE ON DELETE CASCADE,
	author INTEGER REFERENCES profile (id) ON UPDATE CASCADE ON DELETE SET NULL,
	created TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL	
);

CREATE TABLE forum_comment (
	id SERIAL PRIMARY KEY,
	text TEXT NOT NULL,
    id_question INTEGER NOT NULL REFERENCES forum_question (id) ON UPDATE CASCADE ON DELETE CASCADE,
	author INTEGER REFERENCES profile (id) ON UPDATE CASCADE ON DELETE SET NULL,
	created TIMESTAMP WITH TIME zone DEFAULT now() NOT NULL	
);

CREATE TABLE team (
	id SERIAL PRIMARY KEY,
    id_project INTEGER NOT NULL REFERENCES project (id) ON UPDATE CASCADE ON DELETE CASCADE,
	name TEXT NOT NULL,
	creator INTEGER REFERENCES profile (id) ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE member (
	id SERIAL PRIMARY KEY,
    id_project INTEGER NOT NULL REFERENCES project (id) ON UPDATE CASCADE ON DELETE CASCADE,
	id_profile INTEGER NOT NULL REFERENCES profile (id) ON UPDATE CASCADE ON DELETE CASCADE,
	coordinator BOOLEAN NOT NULL,
	CONSTRAINT unique_member UNIQUE (id_profile, id_project)
);

CREATE TABLE team_member (
	id_member INTEGER NOT NULL REFERENCES member (id) ON UPDATE CASCADE ON DELETE CASCADE,
	id_team INTEGER NOT NULL REFERENCES team (id) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT team_member_pk PRIMARY KEY (id_member,id_team)
);

CREATE TABLE assigned (
	id SERIAL PRIMARY KEY,
	id_list INTEGER NOT NULL REFERENCES tasks_list (id) ON UPDATE CASCADE ON DELETE CASCADE,
	id_team INTEGER DEFAULT NULL REFERENCES team (id) ON UPDATE CASCADE ON DELETE CASCADE,
	id_member INTEGER DEFAULT NULL REFERENCES member (id) ON UPDATE CASCADE ON DELETE CASCADE,
	CONSTRAINT chk_xor CHECK ((id_team = NULL AND id_member != NULL) OR (id_team != NULL AND id_member = NULL))
);

-- INDEXES 

CREATE INDEX comment_id ON forum_comment USING Hash (id_question);
CREATE INDEX question_id ON forum_question USING Hash (id_project);    
CREATE INDEX member_id ON member USING Hash (id_profile); 
CREATE INDEX task_list_id ON tasks_list USING Hash (id_project); 
CREATE INDEX task_id ON task USING Hash (id_list);
CREATE INDEX team_id ON team USING Hash (id_project );     
CREATE INDEX team_member_id ON team_member USING Hash (id_team );         
CREATE INDEX search_task ON task USING GIST (to_tsvector('english',name));
CREATE INDEX search_project ON project USING GIST (to_tsvector('english',project.name||' '||coalesce(project.description,'')));
CREATE INDEX search_user ON profile USING GIST (to_tsvector('english',profile.username));

--for some reason postgresql can't recognize ||

-- TRIGGERS 

 -- The user can only create questions in the forums of projects it's a member on
CREATE FUNCTION verify_user_question_create() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS (SELECT * FROM project, member WHERE NEW.id_project = member.id_project AND member.id_profile = NEW.author) 
    THEN
        RETURN NEW;
    END IF;
    RAISE EXCEPTION 'This user is not a member of this project, cant create question!';
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER verify_user_question_create_trigger
    BEFORE INSERT ON forum_question
    FOR EACH ROW
    EXECUTE PROCEDURE verify_user_question_create(); 


-- The user can only create answers to questions in the forums of projects it's a member on 
CREATE FUNCTION verify_user_answer_create() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS (SELECT * FROM project, member, forum_question WHERE NEW.id_question = forum_question.id AND forum_question.id_project = project.id AND  project.id = member.id_project AND member.id_profile = NEW.author) 
    THEN
        RETURN NEW;
    END IF;
    RAISE EXCEPTION 'This user is not a member of this project, cant answer question!';
END
$BODY$
LANGUAGE plpgsql;
 
CREATE TRIGGER verify_user_answer_create_trigger
    BEFORE INSERT ON forum_comment
    FOR EACH ROW
    EXECUTE PROCEDURE verify_user_answer_create(); 


-- The user can only be assigned to lists of tasks of projects in which it's a member on
CREATE FUNCTION verify_user_assigned_create() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS (SELECT * FROM tasks_list, member WHERE NEW.id_list = tasks_list.id AND NEW.id_member = member.id AND member.id_project = tasks_list.id_project) THEN
        RETURN NEW;
    END IF;
	IF EXISTS (SELECT * FROM tasks_list, team WHERE NEW.id_list = tasks_list.id AND NEW.id_team = team.id AND team.id_project = tasks_list.id_project) THEN
		RETURN NEW;
    END IF;
    RAISE EXCEPTION 'This user/team is not a member of this project, cant be assigned!';
END
$BODY$
LANGUAGE plpgsql;
 
CREATE TRIGGER verify_user_assigned_create_trigger
    BEFORE INSERT OR UPDATE ON assigned
    FOR EACH ROW
    EXECUTE PROCEDURE verify_user_assigned_create(); 


-- The user can only be a part of teams of projects in which is a member on    
CREATE FUNCTION verify_user_team_member_create() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF EXISTS (SELECT * FROM team, member WHERE NEW.id_member = member.id AND NEW.id_team = team.id AND team.id_project = member.id_project) 
    THEN
        RETURN NEW;
    END IF;
    RAISE EXCEPTION 'This user is not a member of this project, cant join team!';
END
$BODY$
LANGUAGE plpgsql;
 
CREATE TRIGGER verify_user_team_member_create_trigger
    BEFORE INSERT OR UPDATE ON team_member
    FOR EACH ROW
    EXECUTE PROCEDURE verify_user_team_member_create();


-- The user can only solve tasks it's assigned to
CREATE FUNCTION verify_user_task_update() RETURNS TRIGGER AS
$BODY$
BEGIN
    IF NEW.solver IS NULL
    THEN
        RETURN NEW;
	END IF;
    IF EXISTS (SELECT * FROM team, member, assigned, team_member WHERE ( NEW.id_list = assigned.id_list AND NEW.solver =  member.id_profile AND (member.id = assigned.id_member OR (team_member.id_member=member.id AND team_member.id_team = assigned.id_team))) )
    THEN
        RETURN NEW;
	END IF;
    RAISE EXCEPTION 'This user is not assigned to this task !';
END
$BODY$
LANGUAGE plpgsql;
 
CREATE TRIGGER verify_user_task_update_trigger
    BEFORE INSERT OR UPDATE ON task
    FOR EACH ROW
    EXECUTE PROCEDURE verify_user_task_update();


CREATE FUNCTION update_after_user_removed() RETURNS TRIGGER AS
$BODY$
BEGIN
    UPDATE tasks_list
        SET creator=NULL
        WHERE creator = OLD.id_profile;
    UPDATE task
        SET creator=NULL
        WHERE creator = OLD.id_profile;
    UPDATE task
        SET solver=NULL
        WHERE solver = OLD.id_profile;
    UPDATE comment
        SET author=NULL
        WHERE author = OLD.id_profile;
    UPDATE forum_question
        SET author=NULL
        WHERE author = OLD.id_profile;
    UPDATE forum_comment
        SET author=NULL
        WHERE author = OLD.id_profile;
    UPDATE team
        SET creator=NULL
        WHERE creator = OLD.id_profile;
    RETURN OLD;
END
$BODY$
LANGUAGE plpgsql;

CREATE TRIGGER update_after_user_removed_trigger
    BEFORE DELETE ON member
    FOR EACH ROW
    EXECUTE PROCEDURE update_after_user_removed();


create or replace function getUncompletedProjectTasks(project_id integer)
RETURNS INTEGER as $$
DECLARE uncompleted integer;
BEGIN
	select into uncompleted count(*)
	from project, task, tasks_list
	where project.id = project_id and tasks_list.id_project = project.id and task.id_list = tasks_list.id and task.solver is null;
	if uncompleted is null
		then return 0;
	end if;
	return uncompleted;
END
$$ language plpgsql;


create or replace function getCompletedProjectTasks(project_id integer)
RETURNS INTEGER as $$
DECLARE completed integer;
BEGIN
	select into completed count(*)
	from project, task, tasks_list
	where project.id = project_id and tasks_list.id_project = project.id and task.id_list = tasks_list.id and task.solver is not null;
	if completed is null
		then return 0;
	end if;
	return completed;
END
$$ language plpgsql;



create or replace function getUncompleted(task_list_id integer)
RETURNS INTEGER as $$
DECLARE uncompleted integer;
BEGIN
	select into uncompleted count(*)
	from task, tasks_list
	where task.id_list = tasks_list.id and task.solver is NULL and tasks_list.id = task_list_id
	group by tasks_list.id;
	
	if uncompleted is null
		then return 0;
	end if;
	return uncompleted;	
END
$$ language plpgsql;


create or replace function getCompleted(task_list_id integer)
RETURNS INTEGER as $$
DECLARE uncompleted integer;
BEGIN
	select into uncompleted count(*)
	from task, tasks_list
	where task.id_list = tasks_list.id and task.solver is not NULL and tasks_list.id = task_list_id
	group by tasks_list.id;
	
	if uncompleted is null
		then return 0;
	end if;
	return uncompleted;	
END
$$ language plpgsql;


create or replace function getTotalTasks(task_list_id integer)
RETURNS INTEGER as $$
DECLARE total_tasks integer;
BEGIN
	select into total_tasks count(*)
	from task, tasks_list
	where task.id_list = tasks_list.id and tasks_list.id = task_list_id
	group by tasks_list.id;
	
	if total_tasks is null
		then return 0;
	end if;
	return total_tasks;	
END
$$ language plpgsql;
 



INSERT INTO profile(username, email, name, password) VALUES ('costasalome','costasalome@clix.pt','Costa Salome','$2y$10$JMRyhjnn55HHAioMqmjk1Octqa.q8MgJ8T1QuCVLefdxEHZwVoXYe');
INSERT INTO profile(username, email, name, password) VALUES ('rodrigo00','rodrigo00@gmail.com','Rodrigo Cruz-Alves','$2y$10$JMRyhjnn55HHAioMqmjk1Octqa.q8MgJ8T1QuCVLefdxEHZwVoXYe');
INSERT INTO profile(username, email, name, password) VALUES ('iaracoelho','iaracoelho@sapo.pt','Iara Coelho','$2y$10$JMRyhjnn55HHAioMqmjk1Octqa.q8MgJ8T1QuCVLefdxEHZwVoXYe');
INSERT INTO profile(username, email, name, password) VALUES ('mia37','mia37@gmail.com','Maria Jesus','$2y$10$JMRyhjnn55HHAioMqmjk1Octqa.q8MgJ8T1QuCVLefdxEHZwVoXYe');
INSERT INTO profile(username, email, name, password) VALUES ('pinhowilliam','pinhowilliam@hotmail.com','William Pinho','$2y$10$JMRyhjnn55HHAioMqmjk1Octqa.q8MgJ8T1QuCVLefdxEHZwVoXYe');
INSERT INTO profile(username, email, name, password) VALUES ('joaobarros','joaobarros@gmail.com','Joao Barros','$2y$10$JMRyhjnn55HHAioMqmjk1Octqa.q8MgJ8T1QuCVLefdxEHZwVoXYe');
INSERT INTO profile(username, email, name, password) VALUES ('bryancosta','bryancosta@clix.pt','Bryan Costa','$2y$10$JMRyhjnn55HHAioMqmjk1Octqa.q8MgJ8T1QuCVLefdxEHZwVoXYe');
INSERT INTO profile(username, email, name, password) VALUES ('alexandrefigueiredo','alexandrefigueiredo@gmail.com','Alexandre Figueiredo','$2y$10$JMRyhjnn55HHAioMqmjk1Octqa.q8MgJ8T1QuCVLefdxEHZwVoXYe');
INSERT INTO profile(username, email, name, password) VALUES ('diana20','diana20@gmail.com','Diana Marques','$2y$10$JMRyhjnn55HHAioMqmjk1Octqa.q8MgJ8T1QuCVLefdxEHZwVoXYe');
INSERT INTO profile(username, email, name, password) VALUES ('carolinaneto','carolinaneto@hotmail.com','Carolina Neto','$2y$10$JMRyhjnn55HHAioMqmjk1Octqa.q8MgJ8T1QuCVLefdxEHZwVoXYe');
INSERT INTO profile(username, email, name, password) VALUES ('igor80','igor80@sapo.pt','Igor Soares','$2y$10$JMRyhjnn55HHAioMqmjk1Octqa.q8MgJ8T1QuCVLefdxEHZwVoXYe');
INSERT INTO profile(username, email, name, password) VALUES ('batistaruben','batistaruben@hotmail.com','Ruben Batista','$2y$10$JMRyhjnn55HHAioMqmjk1Octqa.q8MgJ8T1QuCVLefdxEHZwVoXYe');
INSERT INTO profile(username, email, name, password) VALUES ('angelacoelho','angelacoelho@hotmail.com','Angela Coelho','$2y$10$JMRyhjnn55HHAioMqmjk1Octqa.q8MgJ8T1QuCVLefdxEHZwVoXYe');
INSERT INTO profile(username, email, name, password) VALUES ('rafael36','rafael36@clix.pt','Rafael Henriques','$2y$10$JMRyhjnn55HHAioMqmjk1Octqa.q8MgJ8T1QuCVLefdxEHZwVoXYe');
INSERT INTO profile(username, email, name, password) VALUES ('david60','david60@hotmail.com','David Castro','$2y$10$JMRyhjnn55HHAioMqmjk1Octqa.q8MgJ8T1QuCVLefdxEHZwVoXYe');
INSERT INTO profile(username, email, name, password) VALUES ('markmeehan','markmeehan@hotmail.com','Mark Meehan','$2y$10$JMRyhjnn55HHAioMqmjk1Octqa.q8MgJ8T1QuCVLefdxEHZwVoXYe');
INSERT INTO profile(username, email, name, password) VALUES ('carolsoares','carolsoares@hotmail.com','Carolina Soares','$2y$10$JMRyhjnn55HHAioMqmjk1Octqa.q8MgJ8T1QuCVLefdxEHZwVoXYe');
INSERT INTO profile(username, email, name, password) VALUES ('joaoaraujo','joaoaraujo@hotmail.com','Joao Araujo','$2y$10$JMRyhjnn55HHAioMqmjk1Octqa.q8MgJ8T1QuCVLefdxEHZwVoXYe');
INSERT INTO profile(username, email, name, password) VALUES ('miguelrosa','miguelrosa@hotmail.com','Miguel Rosa','$2y$10$JMRyhjnn55HHAioMqmjk1Octqa.q8MgJ8T1QuCVLefdxEHZwVoXYe');
INSERT INTO profile(username, email, name, password) VALUES ('jclfeup','jcl@fe.up.pt','Joao Correia Lopes','$2y$10$JMRyhjnn55HHAioMqmjk1Octqa.q8MgJ8T1QuCVLefdxEHZwVoXYe');

INSERT INTO project(name, description, creator) VALUES ('Covid-19 prediction model','Development of a Support Vector Machine for predicting the future cases of Covid-19, worldwide or in a given country', 20);
INSERT INTO project(name, description, creator) VALUES ('RDA - Riot Detection App','Development of a App for alerting and monitoring Riots. To be used by civilians and law enforcement as a way to keep everyone safe', 20);
INSERT INTO project(name, description, creator) VALUES ('New Sigarra','Major refactor of Sigarra, which has been god awful since its birth', 20);

INSERT INTO tasks_list(name, id_project, creator) VALUES ('Dataset cleaning','1','1');
INSERT INTO tasks_list(name, id_project, creator) VALUES ('SVR development','1','2');
INSERT INTO tasks_list(name, id_project, creator) VALUES ('App pages','2','7');
INSERT INTO tasks_list(name, id_project, creator) VALUES ('Backend','2','8');
INSERT INTO tasks_list(name, id_project, creator) VALUES ('Frontend','3','12');
INSERT INTO tasks_list(name, id_project, creator) VALUES ('Set infrastructure','3','13');

INSERT INTO task(name, description, id_list, creator, solver, category) VALUES ('Fix diverging names for same country','After joining two datasets, there are different names for the same countries, in many cases','1','1',NULL,'Data');
INSERT INTO task(name, description, id_list, creator, solver, category) VALUES ('Join Population Dataset','Since we need more information related to the population of each country, we need to fetch another dataset.','1','2',NULL,'Data');
INSERT INTO task(name, description, id_list, creator, solver, category) VALUES ('Adapt data per million habitants','Current number of cases in the dataset is a cumulative count and not per million. Needs adaptation to number of cases per million habitants.','1','3',NULL,'Data');

INSERT INTO task(name, description, id_list, creator, solver, category) VALUES ('Develop grid search','For finding the optimal parameters for the regression, we need to execute a grid seach.','2','4',NULL,'AI');
INSERT INTO task(name, description, id_list, creator, solver, category) VALUES ('Kernel experiments','We need to find the optimal kernel for the problem at hand. Develop experiments with linear, polynomial and rbf kernels.','2','5',NULL,'AI');
INSERT INTO task(name, description, id_list, creator, solver, category) VALUES ('Clock execution times','We need reports of execution time for model evaluation.','2','6',NULL,'AI');

INSERT INTO task(name, description, id_list, creator, solver, category) VALUES ('Develop map page','A page with the map of the user current area must be displayed.','3','8',NULL,'Frontend');
INSERT INTO task(name, description, id_list, creator, solver, category) VALUES ('Develop profile page','A user must be able to view and edit his profile.','3','7',NULL,'Frontend');
INSERT INTO task(name, description, id_list, creator, solver, category) VALUES ('Develop alert modals','When a user is close to a riot location, a modal shows up alerting the user.','3','8',NULL,'Frontend');

INSERT INTO task(name, description, id_list, creator, solver, category) VALUES ('Guarantee compatibility','Read API docs and guarantee all dependencies can be satisfied.','4','11',NULL,'Backend');
INSERT INTO task(name, description, id_list, creator, solver, category) VALUES ('Import map from API','Import map to app through user location data.','4','11',NULL,'Backend');
INSERT INTO task(name, description, id_list, creator, solver, category) VALUES ('Cookies','Develop cookies','4','10',NULL,'Backend');

INSERT INTO task(name, description, id_list, creator, solver, category) VALUES ('Refactor user page','Refactor user page following mockups.','5','15',NULL,'Frontend');
INSERT INTO task(name, description, id_list, creator, solver, category) VALUES ('Develop missing tuition alert modal','Develop modal alert for missing tuitions.','5','16',NULL,'Frontend');
INSERT INTO task(name, description, id_list, creator, solver, category) VALUES ('Refactor course unit page','Refactor course unit page following mockups.','5','14',NULL,'Frontend');
INSERT INTO task(name, description, id_list, creator, solver, category) VALUES ('Develop parking spot reservation page','Develop page where a student can reserve a parking spot in the Students parking lot.','5','15',NULL,'Frontend');

INSERT INTO task(name, description, id_list, creator, solver, category) VALUES ('Jenkins pipeline','Develop Jenkins pipeline for Sigarra. Run jobs in parallel.','6','16',NULL,'DevOps');
INSERT INTO task(name, description, id_list, creator, solver, category) VALUES ('Docker service for payments','Specify docker service for payments processing.','6','17',NULL,'DevOps');
INSERT INTO task(name, description, id_list, creator, solver, category) VALUES ('Prometheus for data analysis','Integrate Prometheus in project for container data analysis.','6','17',NULL,'DevOps');
INSERT INTO task(name, description, id_list, creator, solver, category) VALUES ('Grafana for graph visualization','Integrate Grafana in project for graph visualization.','6','18',NULL,'DevOps');



INSERT INTO member(id_project, id_profile, coordinator) VALUES ('1','1','False');
INSERT INTO member(id_project, id_profile, coordinator) VALUES ('1','2','False');
INSERT INTO member(id_project, id_profile, coordinator) VALUES ('1','3','False');
INSERT INTO member(id_project, id_profile, coordinator) VALUES ('1','4','False');
INSERT INTO member(id_project, id_profile, coordinator) VALUES ('1','5','False');
INSERT INTO member(id_project, id_profile, coordinator) VALUES ('1','6','False');
INSERT INTO member(id_project, id_profile, coordinator) VALUES ('1','7','False');
INSERT INTO member(id_project, id_profile, coordinator) VALUES ('1','8','False');
INSERT INTO member(id_project, id_profile, coordinator) VALUES ('1','9','False');
INSERT INTO member(id_project, id_profile, coordinator) VALUES ('1','10','False');
INSERT INTO member(id_project, id_profile, coordinator) VALUES ('1','11','False');
INSERT INTO member(id_project, id_profile, coordinator) VALUES ('1','12','False');
INSERT INTO member(id_project, id_profile, coordinator) VALUES ('1','13','False');
INSERT INTO member(id_project, id_profile, coordinator) VALUES ('1','14','False');

INSERT INTO member(id_project, id_profile, coordinator) VALUES ('2','4','False');
INSERT INTO member(id_project, id_profile, coordinator) VALUES ('2','5','False');
INSERT INTO member(id_project, id_profile, coordinator) VALUES ('2','6','False');
INSERT INTO member(id_project, id_profile, coordinator) VALUES ('2','7','False');
INSERT INTO member(id_project, id_profile, coordinator) VALUES ('2','8','False');
INSERT INTO member(id_project, id_profile, coordinator) VALUES ('2','9','False');
INSERT INTO member(id_project, id_profile, coordinator) VALUES ('2','10','False');
INSERT INTO member(id_project, id_profile, coordinator) VALUES ('2','11','False');
INSERT INTO member(id_project, id_profile, coordinator) VALUES ('2','12','False');
INSERT INTO member(id_project, id_profile, coordinator) VALUES ('2','13','False');
INSERT INTO member(id_project, id_profile, coordinator) VALUES ('2','14','False');
INSERT INTO member(id_project, id_profile, coordinator) VALUES ('2','15','False');
INSERT INTO member(id_project, id_profile, coordinator) VALUES ('2','16','False');
INSERT INTO member(id_project, id_profile, coordinator) VALUES ('2','17','False');

INSERT INTO member(id_project, id_profile, coordinator) VALUES ('3','6','False');
INSERT INTO member(id_project, id_profile, coordinator) VALUES ('3','7','False');
INSERT INTO member(id_project, id_profile, coordinator) VALUES ('3','8','False');
INSERT INTO member(id_project, id_profile, coordinator) VALUES ('3','9','False');
INSERT INTO member(id_project, id_profile, coordinator) VALUES ('3','10','False');
INSERT INTO member(id_project, id_profile, coordinator) VALUES ('3','11','False');
INSERT INTO member(id_project, id_profile, coordinator) VALUES ('3','12','False');
INSERT INTO member(id_project, id_profile, coordinator) VALUES ('3','13','False');
INSERT INTO member(id_project, id_profile, coordinator) VALUES ('3','14','False');
INSERT INTO member(id_project, id_profile, coordinator) VALUES ('3','15','False');
INSERT INTO member(id_project, id_profile, coordinator) VALUES ('3','16','False');
INSERT INTO member(id_project, id_profile, coordinator) VALUES ('3','17','False');
INSERT INTO member(id_project, id_profile, coordinator) VALUES ('3','18','False');
INSERT INTO member(id_project, id_profile, coordinator) VALUES ('3','19','False');

INSERT INTO member(id_project, id_profile, coordinator) VALUES ('1','20','True');
INSERT INTO member(id_project, id_profile, coordinator) VALUES ('2','20','True');
INSERT INTO member(id_project, id_profile, coordinator) VALUES ('3','20','True');


INSERT INTO comment(text, id_task, author) VALUES ('This needs work and focum.','1','1');
INSERT INTO comment(text, id_task, author) VALUES ('Maybe we can grid search the optimal kernel as well.','4','4');
INSERT INTO comment(text, id_task, author) VALUES ('This should not be an issue.','10','15');
INSERT INTO comment(text, id_task, author) VALUES ('This page really needs work.','13','16');
INSERT INTO comment(text, id_task, author) VALUES ('Getting the parallel build right is top priority.','17','17');
INSERT INTO comment(text, id_task, author) VALUES ('This will be really useful for container monitoring.','19','19');


-- INSERT INTO forum_question(text, id_project, author) VALUES ('Natus enim saepe.Expedita mollitia repellendus iusto. Corporis rerum quod excepturi facilis voluptates dolorem.','1','1');
-- INSERT INTO forum_question(text, id_project, author) VALUES ('2 Natus enim saepe.Expedita mollitia repellendus iusto. Corporis rerum quod excepturi facilis voluptates dolorem.','1','1');
-- INSERT INTO forum_question(text, id_project, author) VALUES ('Harum voluptatum molestias quis.Expedita mollitia repellendus iusto. Repturi facilis voluptates dolorem.','2','2');
-- INSERT INTO forum_question(text, id_project, author) VALUES ('Expedita mollitia repellendus iusto. Corporis rerum quod excepturi facilis voluptates dolorem.','3','3');


-- INSERT INTO forum_comment(text, id_question, author) VALUES ('Odio temporibus dolor exercitationem provident odio.Vitae at ea rerum sit. Nulla quas possimus maxime sint consequuntur.','1','1');
-- INSERT INTO forum_comment(text, id_question, author) VALUES ('2 Odio temporibus dolor exercitationem provident odio.Vitae at ea rerum sit. Nulla quas possimus maxime sint consequuntur.','1','1');
-- INSERT INTO forum_comment(text, id_question, author) VALUES ('2 Odio temporibus dolor exercitationem provident odio.Vitae at ea rerum sit. Nulla quas possimus maxime sint consequuntur.','2','1');
-- INSERT INTO forum_comment(text, id_question, author) VALUES ('Odio temporibus dolor exercitationem provident odio.Vitae at ea rerum sit. Nulla quas possimus maxime sint consequuntur.','3','2');
-- INSERT INTO forum_comment(text, id_question, author) VALUES ('Odio temporibus dolor exercitationem provident odio.Vitae at ea rerum sit. Nulla quas possimus maxime sint consequuntur.','4','3');

INSERT INTO team(id_project, name, creator) VALUES ('1','Team Paris','1');
INSERT INTO team(id_project, name, creator) VALUES ('1','Team Melbourne','2');
INSERT INTO team(id_project, name, creator) VALUES ('2','Team Dublin','11');
INSERT INTO team(id_project, name, creator) VALUES ('2','Team Toronto','12');
INSERT INTO team(id_project, name, creator) VALUES ('3','Team Rio','18');
INSERT INTO team(id_project, name, creator) VALUES ('3','Team Denver','17');


INSERT INTO team_member(id_member, id_team) VALUES ('1','1');
INSERT INTO team_member(id_member, id_team) VALUES ('2','1');
INSERT INTO team_member(id_member, id_team) VALUES ('3','1');
INSERT INTO team_member(id_member, id_team) VALUES ('4','1');

INSERT INTO team_member(id_member, id_team) VALUES ('13','2');
INSERT INTO team_member(id_member, id_team) VALUES ('14','2');
INSERT INTO team_member(id_member, id_team) VALUES ('5','2');
INSERT INTO team_member(id_member, id_team) VALUES ('6','2');

INSERT INTO team_member(id_member, id_team) VALUES ('16','3');
INSERT INTO team_member(id_member, id_team) VALUES ('20','3');
INSERT INTO team_member(id_member, id_team) VALUES ('17','3');
INSERT INTO team_member(id_member, id_team) VALUES ('18','3');

INSERT INTO team_member(id_member, id_team) VALUES ('19','4');
INSERT INTO team_member(id_member, id_team) VALUES ('21','4');
INSERT INTO team_member(id_member, id_team) VALUES ('22','4');
INSERT INTO team_member(id_member, id_team) VALUES ('23','4');

INSERT INTO team_member(id_member, id_team) VALUES ('33','5');
INSERT INTO team_member(id_member, id_team) VALUES ('34','5');
INSERT INTO team_member(id_member, id_team) VALUES ('35','5');
INSERT INTO team_member(id_member, id_team) VALUES ('36','5');

INSERT INTO team_member(id_member, id_team) VALUES ('29','6');
INSERT INTO team_member(id_member, id_team) VALUES ('30','6');
INSERT INTO team_member(id_member, id_team) VALUES ('31','6');
INSERT INTO team_member(id_member, id_team) VALUES ('32','6');


INSERT INTO assigned(id_list, id_team) VALUES (1,1);
INSERT INTO assigned(id_list, id_team) VALUES (2,2);
INSERT INTO assigned(id_list, id_team) VALUES (3,3);
INSERT INTO assigned(id_list, id_team) VALUES (4,4);
INSERT INTO assigned(id_list, id_team) VALUES (5,5);
INSERT INTO assigned(id_list, id_team) VALUES (6,6);
