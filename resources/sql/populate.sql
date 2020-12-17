INSERT INTO profile(username, email, name, password) VALUES ('costasalome','evafonseca@clix.pt','Anita Coelho','X5E3eFneE9');
INSERT INTO profile(username, email, name, password) VALUES ('rodrigo00','eduardo97@gmail.com','Mateus Cruz-Alves','X5ruDThVs2');
INSERT INTO profile(username, email, name, password) VALUES ('iaracoelho','fborges@sapo.pt','Fabiana Sá','ynC3QFOmf9');
INSERT INTO profile(username, email, name, password) VALUES ('mia37','rochagil@gmail.com','Gabriela Jesus','RppNBW3g6u');
INSERT INTO profile(username, email, name, password) VALUES ('pinhowilliam','catarina28@hotmail.com','Ângela Vaz','CB2D0pre2c');
INSERT INTO profile(username, email, name, password) VALUES ('joaobarros','hugo95@gmail.com','Eduarda Correia-Andrade','N5f28Lpnv9');
INSERT INTO profile(username, email, name, password) VALUES ('bryancosta','benjamimfernandes@clix.pt','Kyara do Matias','z981NxFhjQ');
INSERT INTO profile(username, email, name, password) VALUES ('alexandrefigueiredo','camposiris@gmail.com','Benedita Lopes','8W3zrKvwXm');
INSERT INTO profile(username, email, name, password) VALUES ('diana20','paivalia@gmail.com','Letícia-Igor Marques','PsqIzztj5X');
INSERT INTO profile(username, email, name, password) VALUES ('carolinaneto','luana12@hotmail.com','Madalena Loureiro','ibOuIuTk2O');
INSERT INTO profile(username, email, name, password) VALUES ('igor80','pachecojose@sapo.pt','Isabel Magalhães-Soares','u3ah1BjkCr');
INSERT INTO profile(username, email, name, password) VALUES ('batistaruben','tmarques@hotmail.com','Miguel Pinto','VsJsAIeu7S');
INSERT INTO profile(username, email, name, password) VALUES ('angelacoelho','vassuncao@hotmail.com','Alice Lopes','V95Vi9zhbN');
INSERT INTO profile(username, email, name, password) VALUES ('rafael36','jessica87@clix.pt','Samuel Henriques','M6uLWNa7rT');
INSERT INTO profile(username, email, name, password) VALUES ('david60','edgar14@hotmail.com','Camila Castro','WaXkuGi876');

INSERT INTO project(name, description, creator) VALUES ('Project laboriosam','Soluta dolore quam deleniti distinctio laboriosam vel. Consequatur ab placeat reiciendis molestiae temporibus. Tempore magnam similique laboriosam velit modi.',11);
INSERT INTO project(name, description, creator) VALUES ('Project quis','Odio necessitatibus ex quo modi minima error. Totam aut error soluta commodi odit. Iure ex aspernatur eum illo amet.Id eum voluptas vel vero.',3);
INSERT INTO project(name, description, creator) VALUES ('Project molestiae','Voluptatem illum modi perferendis eligendi dolore recusandae. Sapiente soluta libero unde et quam. At nulla occaecati maiores.',8);

INSERT INTO member(id_project, id_profile, coordinator) VALUES ('1','1','False');
INSERT INTO member(id_project, id_profile, coordinator) VALUES ('2','2','False');
INSERT INTO member(id_project, id_profile, coordinator) VALUES ('3','3','False');
INSERT INTO member(id_project, id_profile, coordinator) VALUES ('3','4','False');
INSERT INTO member(id_project, id_profile, coordinator) VALUES ('3','5','False');

INSERT INTO tasks_list(name, id_project, creator) VALUES ('List molestiae','1','7');
INSERT INTO tasks_list(name, id_project, creator) VALUES ('List perferendis','3','8');
INSERT INTO tasks_list(name, id_project, creator) VALUES ('List aut','2','11');
INSERT INTO tasks_list(name, id_project, creator) VALUES ('List auxa','1','3');
INSERT INTO tasks_list(name, id_project, creator) VALUES ('List iuja','3','4');
INSERT INTO tasks_list(name, id_project, creator) VALUES ('List pike','3','1');

INSERT INTO task(name, description, id_list, creator, solver, category) VALUES ('Task harum','Accusantium itaque possimus maiores distinctio. Et harum perspiciatis dolorem eius.Pariatur iure tempora quos dolores inventore. Quam quod non culpa. Quod atque voluptate.','2','4',NULL,'Category placeat');
INSERT INTO task(name, description, id_list, creator, solver, category) VALUES ('Task voluptates','Officia suscipit laudantium. Porro explicabo suscipit sunt magni iusto dolorum.','1','11',NULL,'Category minus');
INSERT INTO task(name, description, id_list, creator, solver, category) VALUES ('Task assumenda','Recusandae laudantium consectetur recusandae. Quaerat ratione autem quos odio blanditiis sed. Maxime saepe culpa dolores ullam assumenda nam quos.','2','1',NULL,'Category atque');
INSERT INTO task(name, description, id_list, creator, solver, category) VALUES ('Task sit','Id harum fugit maxime. Enim veritatis expedita nemo. Nulla corrupti beatae a quasi.','3','7',NULL,'Category laboriosam');
INSERT INTO task(name, description, id_list, creator, solver, category) VALUES ('Task iure','Dignissimos similique autem voluptate aliquid. Quaerat officia fugit harum voluptatibus.','1','15',NULL,'Category praesentium');
INSERT INTO task(name, description, id_list, creator, solver, category) VALUES ('Task rerum','Sapiente quibusdam iusto ut consequuntur officia iusto. Sequi cupiditate placeat.Cumque eveniet tenetur eum aspernatur sint omnis id. Dignissimos odio vitae culpa itaque dolor libero.','2','11',NULL,'Category laborum');
INSERT INTO task(name, description, id_list, creator, solver, category) VALUES ('Task ratione','Autem quaerat ipsa odit architecto totam velit.Et vitae officiis inventore deleniti. Cum facilis asperiores veritatis itaque.','3','13',NULL,'Category sint');
INSERT INTO task(name, description, id_list, creator, solver, category) VALUES ('Task iure','Neque eaque dolorem ex. Officia doloribus dolores voluptatem. Inventore sapiente eum rem enim reiciendis delectus eos.Laboriosam nesciunt itaque. Perferendis ducimus perferendis ipsum nam cum.','3','7',NULL,'Category animi');
INSERT INTO task(name, description, id_list, creator, solver, category) VALUES ('Task dolores','Ex distinctio exercitationem.Deleniti vero deleniti iure odit beatae maiores iusto. Qui laborum veritatis. Perspiciatis fugiat explicabo.','2','2',NULL,'Category dicta');
INSERT INTO task(name, description, id_list, creator, solver, category) VALUES ('Task amet','Sunt earum eligendi libero consectetur repellat. Totam voluptatibus quo omnis cumque impedit. Dolore perspiciatis nisi ab voluptatum iure.','1','3',NULL,'Category ipsa');
INSERT INTO task(name, description, id_list, creator, solver, category) VALUES ('Task quos','Nemo perferendis amet dolor eveniet sunt assumenda. Dolore soluta numquam. Ipsam nisi eos voluptas nesciunt.','1','1',NULL,'Category libero');
INSERT INTO task(name, description, id_list, creator, solver, category) VALUES ('Task possimus','Sed laborum temporibus accusantium necessitatibus.Dolores ab vel soluta dignissimos eum quidem. Est labore soluta dolorum consequatur. Neque earum omnis id sed velit.','1','9',NULL,'Category nostrum');
INSERT INTO task(name, description, id_list, creator, solver, category) VALUES ('Task minima','Inventore doloremque minima molestias et dolores.Excepturi officia voluptates eveniet cum. Velit eaque quas aspernatur. Atque est officia ad beatae ab perferendis.','2','10',NULL,'Category dignissimos');
INSERT INTO task(name, description, id_list, creator, solver, category) VALUES ('Task voluptas','Nihil assumenda iste dolore unde necessitatibus dolorum. Doloribus officiis quis doloremque. Soluta id quos neque.','3','9',NULL,'Category quibusdam');
INSERT INTO task(name, description, id_list, creator, solver, category) VALUES ('Task autem','Dolor accusantium sit quam commodi animi.Nisi beatae dicta officiis facilis. Aliquam sed expedita mollitia hic aperiam magni.','1','2',NULL,'Category optio');
INSERT INTO task(name, description, id_list, creator, solver, category) VALUES ('Task at','Voluptas aliquid pariatur similique minus error dicta. Laboriosam autem commodi expedita pariatur.','1','2',NULL,'Category voluptates');
INSERT INTO task(name, description, id_list, creator, solver, category) VALUES ('Task perferendis','Modi iure animi hic ipsa dolore. Fuga architecto doloremque aperiam quisquam ut.Nam eligendi voluptas consectetur quae similique dolores. Veniam vitae voluptas unde voluptates soluta.','2','13',NULL,'Category vel');
INSERT INTO task(name, description, id_list, creator, solver, category) VALUES ('Task quas','Quam doloremque qui facilis a. Accusantium rem molestiae illo.Ipsam amet fugit harum. Sit veniam est itaque minima recusandae.','2','6',NULL,'Category rem');
INSERT INTO task(name, description, id_list, creator, solver, category) VALUES ('Task reiciendis','Veritatis illo quidem assumenda architecto eaque. Ad sequi itaque saepe quas repellendus voluptas.','1','13',NULL,'Category voluptas');
INSERT INTO task(name, description, id_list, creator, solver, category) VALUES ('Task quo','Laboriosam dolorum et necessitatibus quo illo. Porro recusandae alias earum necessitatibus maiores incidunt pariatur.Nesciunt quos atque quaerat expedita eum. Amet vitae sint eius deserunt eaque.','3','13',NULL,'Category quasi');

INSERT INTO comment(text, id_task, author) VALUES ('Perferendis consectetur unde. Voluptas vitae consectetur soluta placeat sint.Beatae consequuntur esse dicta. Aspernatur eius architecto.','8','1');
INSERT INTO comment(text, id_task, author) VALUES ('Consectetur unde. Voluptas vitae consectetur soluta placeat sint.Beatae consequuntur esse dicta. Ex earum id quisquam.','2','2');
INSERT INTO comment(text, id_task, author) VALUES ('Perferendis  consectetur soluta placeat sint.Ex earum id quisquam. Aspernatur eius architecto.','1','3');
INSERT INTO comment(text, id_task, author) VALUES ('Voluptas vitae consectetur soluta placeat sint.Beatae consequuntur esse dicta. Ex earum id quisquam. Aspernatur eius architecto.','5','4');
INSERT INTO comment(text, id_task, author) VALUES ('Perferendis Voluptas vitae consectetur soluta placeat sint.Beatae consequuntur esse dicta. Ex earum id quisquam. Aspernatur eius architecto.','7','5');
INSERT INTO comment(text, id_task, author) VALUES ('Perferendis consectetur unde. Voluptas soluta placeat sint.Beatae consequuntur esse dicta. Ex earum id quisquam. Aspernatur eius architecto.','9','6');


INSERT INTO forum_question(text, id_project, author) VALUES ('Natus enim saepe.Expedita mollitia repellendus iusto. Corporis rerum quod excepturi facilis voluptates dolorem.','1','1');
INSERT INTO forum_question(text, id_project, author) VALUES ('Harum voluptatum molestias quis.Expedita mollitia repellendus iusto. Repturi facilis voluptates dolorem.','2','2');
INSERT INTO forum_question(text, id_project, author) VALUES ('Expedita mollitia repellendus iusto. Corporis rerum quod excepturi facilis voluptates dolorem.','3','3');


INSERT INTO forum_comment(text, id_question, author) VALUES ('Odio temporibus dolor exercitationem provident odio.Vitae at ea rerum sit. Nulla quas possimus maxime sint consequuntur.','1','1');
INSERT INTO forum_comment(text, id_question, author) VALUES ('Odio temporibus dolor exercitationem provident odio.Vitae at ea rerum sit. Nulla quas possimus maxime sint consequuntur.','2','2');
INSERT INTO forum_comment(text, id_question, author) VALUES ('Odio temporibus dolor exercitationem provident odio.Vitae at ea rerum sit. Nulla quas possimus maxime sint consequuntur.','3','3');

INSERT INTO team(id_project, name, creator) VALUES ('3','Team illum','1');
INSERT INTO team(id_project, name, creator) VALUES ('2','Team assumenda','2');
INSERT INTO team(id_project, name, creator) VALUES ('3','Team incidunt','3');
INSERT INTO team(id_project, name, creator) VALUES ('1','Team aut','4');
INSERT INTO team(id_project, name, creator) VALUES ('1','Team cupiditate','5');

INSERT INTO team_member(id_member, id_team) VALUES ('1','4');
INSERT INTO team_member(id_member, id_team) VALUES ('2','2');
INSERT INTO team_member(id_member, id_team) VALUES ('3','3');
INSERT INTO team_member(id_member, id_team) VALUES ('3','1');
INSERT INTO team_member(id_member, id_team) VALUES ('1','5');

INSERT INTO assigned(id_list, id_team) VALUES (2,1);
INSERT INTO assigned(id_list, id_team) VALUES (5,1);
INSERT INTO assigned(id_list, id_team) VALUES (3,2);
INSERT INTO assigned(id_list, id_team) VALUES (1,4);
INSERT INTO assigned(id_list, id_team) VALUES (4,5);
