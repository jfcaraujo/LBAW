<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Task;
use App\TasksList;
use App\Assigned;
use App\Team;
use App\Comment;
use App\Member;
use App\Project;
use App\User;

class TaskController extends Controller
{
  public function show_task($project_id, $task_id)
  {
    if (!Auth::check()) return redirect('/login');
    if (!is_numeric($project_id)||!is_numeric($task_id))return redirect('/home');
    $project = Project::find($project_id);
    $this->authorize('isMember', $project);

    $task = Task::find($task_id);
    $comments = Comment::whereId_task($task_id)->get();
    if ($task->creator != null)
      $creator = User::find($task->creator);
    else {
      $creator = new User;
      $creator->username = "[deleted user]";
    }
    if ($task->solved != null) {
      if ($task->solver != null)
        $solver = User::find($task->solver);
      else {
        $solver = new User;
        $solver->username = "[deleted user]";
      }
    } else $solver = null;
    return view('pages.task', ['project_id' => $project_id, 'task' => $task, 'comments' => $comments, 'creator' => $creator, 'solver' => $solver]);
  }

  public function show_tasks($project_id, $list_id)
  {
    if (!Auth::check()) return redirect('/login');
    if (!is_numeric($project_id)||!is_numeric($list_id))return redirect('/home');
    $project = Project::find($project_id);
    $this->authorize('isMember', $project);

    $tasks = Task::whereId_list($list_id)->get();
    $members_through_team = User::join('member', 'member.id_profile', '=', 'profile.id')->join('team_member', 'team_member.id_member', '=', 'member.id')
      ->join('assigned', 'assigned.id_team', '=', 'team_member.id_team')->select('profile.username')->where('assigned.id_list', $list_id)->distinct('profile.username')->get();

    $members_through_team_array = array();
    foreach ($members_through_team as $member) {
      array_push($members_through_team_array, $member->username);
    }
    $members_added_singularly = User::join('member', 'member.id_profile', '=', 'profile.id')
      ->join('assigned', 'assigned.id_member', '=', 'member.id')->select('profile.username')->where('assigned.id_list', $list_id)->distinct('profile.username')->get();

    $members_added_singularly_array = array();
    foreach ($members_added_singularly as $member) {
      array_push($members_added_singularly_array, $member->username);
    }

    $possible_solved_by = array_unique(array_merge($members_through_team_array, $members_added_singularly_array), SORT_REGULAR);

    return view('pages.tasks', ['possible_solved_by' => $possible_solved_by, 'tasks' => $tasks, 'project_id' => $project_id, 'list_id' => $list_id]);
  }

  public function show_task_lists($project_id)
  {
    if (!Auth::check()) return redirect('/login');
    if (!is_numeric($project_id))return redirect('/home');
    $project = Project::find($project_id);
    $this->authorize('isMember', $project);

    $lists = DB::select('select tasks_list.*, getTotalTasks(tasks_list.id) as total_tasks,getUncompleted(tasks_list.id) as uncompleted, getCompleted(tasks_list.id) as completed 
    from tasks_list
    where tasks_list.id_project =? order by id
    ', [$project_id]);


    $teamsArray = array();
    $membersArray = array();

    foreach ($lists as $list) {
      $list_id = $list->id;
      $teams = Team::whereId_project($project_id)->whereNotExists(
        function ($query) use ($list_id) {
          $query->select(DB::raw(1))->from('assigned')->whereId_list($list_id)->whereRaw('assigned.id_team = team.id');
        }
      )->get();
      $teamsArray[$list->id] = $teams;

      $aux_members = User::join('member', 'member.id_profile', '=', 'profile.id')->join('assigned', 'assigned.id_member', '=', 'member.id')->select('profile.*')->where('member.id_project', $project_id)->where('assigned.id_list', $list_id)->get();
      $members = array();
      foreach ($aux_members as $aux_member) {
        array_push($members, $aux_member->username);
      }

      $membersArray[$list->id] = $members;
    }
    $teams = Team::whereId_project($project_id)->get();
    $members = User::join('member', 'member.id_profile', '=', 'profile.id')->select('profile.*')->where('member.id_project', $project_id)->get();
    return view('pages.task_lists', ['members' => $members, 'membersArray' => $membersArray, 'teamsArray' => $teamsArray, 'teams' => $teams, 'lists' => $lists, 'project_id' => $project_id]);
  }

  public function remove_task($task_id)
  {
    if (!Auth::check()) return redirect('/login');
    if (!is_numeric($task_id))return redirect('/home');
    $task = Task::find($task_id);
    $task_list = TasksList::find($task->id_list);
    $project = Project::find($task_list->id_project);
    $this->authorize('isAdmin', $project);

    $task->delete();

    return redirect()->route('projectTaskList', ['id' => $project->id]);
  }

  public function remove_task_ajax(Request $request)
  {
    if (!Auth::check()) return redirect('/login');
    if (!is_numeric($request['proj_id'])||!is_numeric($request['task_id']))return redirect('/home');
    $project = Project::find($request['proj_id']);
    $this->authorize('isAdmin', $project);

    $task = Task::find($request['task_id']);
    $task->delete();

    return $request['task_id'];
  }


  public function create_task_list(Request $request)
  {
    if (!Auth::check()) return redirect('/login');
    $project_id = $request['project_id'];
    if (!is_numeric($project_id))return redirect('/home');
    $project = Project::find($project_id);
    $this->authorize('isAdmin', $project);

    $name = $request->input('name');
    $data = json_decode($request['teams']);

    $task_list = new TasksList;
    $task_list->name = $name;
    $task_list->id_project = $project_id;
    $task_list->creator = Auth::id();
    $task_list->save();

    $list_id = $task_list->id;
    $task_list->total_tasks = 0;
    $task_list->completed = 0;
    $task_list->uncompleted = 0;

    foreach ($data as $team_name) {
      $assigned = new Assigned;
      $assigned->id_list = $list_id;
      $assigned->id_team = Team::whereName($team_name)->get()[0]->id;
      $assigned->save();
    }
    $teams = Team::whereId_project($project_id)->whereNotExists(
      function ($query) use ($list_id) {
        $query->select(DB::raw(1))->from('assigned')->whereId_list($list_id)->whereRaw('assigned.id_team = team.id');
      }
    )->get();

    $members = User::join('member', 'member.id_profile', '=', 'profile.id')->select('profile.*')->where('member.id_project', $project_id)
      ->get(); //can only assign teams on create


    $view = view('partials.taskslistcard')->with(['members' => $members, 'teams' => $teams, 'project_id' => $project_id, 'list' => $task_list, 'membersAssigned' => []]);
    return array("$list_id", "$view");
  }


  public function create_task(Request $request)
  {
    if (!Auth::check()) return redirect('/login');
    $project_id = $request->input('project_id');
    $list_id = $request->input('list_id');
    if (!is_numeric($project_id)||!is_numeric($list_id))return redirect('/home');
    $this->authorize('isAdmin', Project::find($project_id));

    $name = $request->input('name');
    $description = $request->input('description');
    $category = $request->input('category');

    $task = new Task;
    $task->name = $name;
    $task->id_list = $list_id;
    $task->creator = Auth::id();
    $task->description = $description;
    $task->category = $category;
    $task->save();

    $members_through_team = User::join('member', 'member.id_profile', '=', 'profile.id')->join('team_member', 'team_member.id_member', '=', 'member.id')
      ->join('assigned', 'assigned.id_team', '=', 'team_member.id_team')->select('profile.username')->where('assigned.id_list', $list_id)->distinct('profile.username')->get();

    $members_through_team_array = array();
    foreach ($members_through_team as $member) {
      array_push($members_through_team_array, $member->username);
    }
    $members_added_singularly = User::join('member', 'member.id_profile', '=', 'profile.id')
      ->join('assigned', 'assigned.id_member', '=', 'member.id')->select('profile.username')->where('assigned.id_list', $list_id)->distinct('profile.username')->get();

    $members_added_singularly_array = array();
    foreach ($members_added_singularly as $member) {
      array_push($members_added_singularly_array, $member->username);
    }

    $possible_solved_by = array_unique(array_merge($members_through_team_array, $members_added_singularly_array), SORT_REGULAR);

    $view = view('partials.task', ['possible_solved_by' => $possible_solved_by, 'task' => $task, 'project_id' => $project_id]);

    return array("$task->id", "$view");
  }


  public function edit_task(Request $request)
  {
    if (!Auth::check()) return redirect('/login');
    $task_id = $request->input('task_id');
    if (!is_numeric($task_id))return redirect('/home');
    $name = $request->input('name');
    $description = $request->input('description');
    $category = $request->input('category');

    $task = Task::find($task_id);
    $task_list = TasksList::find($task->id_list);
    $project = Project::find($task_list->id_project);
    $this->authorize('isAdmin', $project);

    $task->name = $name;
    $task->description = $description;
    $task->category = $category;
    $task->save();

    return array("$task_id", "$name", "$category");
  }


  public function edit_task_lists(Request $request)
  {
    if (!Auth::check()) return redirect('/login');
    $list_id = $request['list_id'];
    if (!is_numeric($list_id))return redirect('/home');
    $name = $request['name'];
    $task_list = TasksList::find($list_id);

    $id_project = $task_list->id_project;
    $this->authorize('isAdmin', Project::find($id_project));

    $task_list->name = $name;
    $task_list->save();

    return array("$list_id", "$name");
  }

  public function assign(Request $request)
  {
    if (!Auth::check()) return redirect('/login');
    $list_id = $request->input('list_id');
    $project_id = $request->input('project_id');
    if (!is_numeric($project_id)||!is_numeric($list_id))return redirect('/home');
    $this->authorize('isAdmin', Project::find($project_id));

    $teams = json_decode($_POST['teams']);
    $members = json_decode($_POST['members']);

    if (!empty($teams)) {
      foreach ($teams as $team) {
        $assigned = new Assigned;
        $assigned->id_list = $list_id;
        $assigned->id_team = Team::whereName($team)->get()[0]->id;
        $assigned->save();
      }
    }

    if (!empty($members)) {
      foreach ($members as $member) {
        $assigned = new Assigned;
        $assigned->id_list = $list_id;
        $assigned->id_member = Member::join('profile', 'profile.id', '=', 'member.id_profile')->select('member.id')->where('profile.username', $member)
          ->where('member.id_project', $project_id)->get()[0]->id;
        $assigned->save();
      }
    }
    $task_list = DB::select('select tasks_list.*, getTotalTasks(tasks_list.id) as total_tasks,getUncompleted(tasks_list.id) as uncompleted, getCompleted(tasks_list.id) as completed 
    from tasks_list
    where tasks_list.id =? ', [$list_id])[0];

    $teams = Team::whereId_project($project_id)->whereNotExists(
      function ($query) use ($list_id) {
        $query->select(DB::raw(1))->from('assigned')->whereId_list($list_id)->whereRaw('assigned.id_team = team.id');
      }
    )->get();
    $aux_members = User::join('member', 'member.id_profile', '=', 'profile.id')->join('assigned', 'assigned.id_member', '=', 'member.id')->select('profile.*')->where('member.id_project', $project_id)->where('assigned.id_list', $list_id)->get();
    $membersAssigned = array();
    foreach ($aux_members as $aux_member) {
      array_push($membersAssigned, $aux_member->username);
    }
    $members = User::join('member', 'member.id_profile', '=', 'profile.id')->select('profile.*')->where('member.id_project', $project_id)->get();
    $view = view('partials.taskslistcard')->with(['members' => $members, 'teams' => $teams, 'project_id' => $project_id, 'list' => $task_list, 'membersAssigned' => $membersAssigned]);
    return array("$list_id", "$view");
  }

  public function add_comment(Request $request)
  {
    if (!Auth::check()) return redirect('/login');
    $task_id = $request->input('task_id');
    if (!is_numeric($task_id))return redirect('/home');
    $text = $request->input('text');
    $task = Task::find($task_id);
    $task_list = TasksList::find($task->id_list);
    $project = Project::find($task_list->id_project);
    $this->authorize('isMember', $project);

    $comment = new Comment;
    $comment->text = $text;
    $comment->id_task = $task_id;
    $comment->author = Auth::id();
    $comment->save();

    return view('partials.comment', ['comment' => $comment,'project_id'=>$project->id]);
  }

  public function remove_comment(Request $request)
  {
    if (!Auth::check()) return redirect('/login');
    if (!is_numeric($request['comment_id']))return redirect('/home');
    $comment = Comment::find($request['comment_id']);
    $task = Task::find($comment->id_task);
    $task_list = TasksList::find($task->id_list);
    $project = Project::find($task_list->id_project);
    $this->authorize('isAdmin', $project);
    $comment->delete();

    return $request['comment_id'];
  }

  public function solve_task(Request $request)
  {
    if (!Auth::check()) return redirect('/login');
    $task_id = $request->input('task_id');
    if (!is_numeric($task_id))return redirect('/home');
    $solver = $request->input('solver');
    $task = Task::find($task_id);
    $task_list = TasksList::find($task->id_list);
    $project = Project::find($task_list->id_project);
    $this->authorize('isMember', $project);

    $user = User::whereUsername($solver)->get()[0];
    $task->solver = $user->id;
    $task->save();
    DB::select('update task
      set solved=CURRENT_TIMESTAMP
      where task.id = ?', [$task_id]);
    $task = Task::find($task_id);

    $view = view('partials.task', ['task' => $task, 'project_id' => $project->id, 'possible_solved_by' => []]);
    return array($task->id, "$view");
  }

  public function delete_task_list(Request $request)
  {
    if (!Auth::check()) return redirect('/login');
    $list_id = $request['list_id'];
    if (!is_numeric($list_id))return redirect('/home');
    $task_list = TasksList::find($list_id);
    $project_id = $task_list->id_project;
    $this->authorize('isAdmin', Project::find($project_id));

    $task_list->delete();

    return $list_id;
  }


  public function searchTaskList(Request $request)
  {
    if (!Auth::check()) return redirect('/login');
    $name = "%" . $request['name'] . "%";
    $project_id =  $request['id'];
    if (!is_numeric($project_id))return redirect('/home');
    $this->authorize('isMember', Project::find($project_id));
    $view = '';
    $lists = DB::select('select tasks_list.*, getTotalTasks(tasks_list.id) as total_tasks,getUncompleted(tasks_list.id) as uncompleted, getCompleted(tasks_list.id) as completed 
    from tasks_list
    where tasks_list.id_project = ? and tasks_list.name ILIKE ?
    ', [$project_id, $name]);
    $members = User::join('member', 'member.id_profile', '=', 'profile.id')->select('profile.*')->where('member.id_project', $project_id)->get();

    foreach ($lists as $list) {
      $list_id = $list->id;
      $teams = Team::whereId_project($project_id)->whereNotExists(
        function ($query) use ($list_id) {
          $query->select(DB::raw(1))->from('assigned')->whereId_list($list_id)->whereRaw('assigned.id_team = team.id');
        }
      )->get();

      $aux_members = User::join('member', 'member.id_profile', '=', 'profile.id')->join('assigned', 'assigned.id_member', '=', 'member.id')->select('profile.*')->where('member.id_project', $project_id)->get();
      $membersAssigned = array();
      foreach ($aux_members as $aux_member) {
        array_push($membersAssigned, $aux_member->username);
      }
      $view = $view . view('partials.taskslistcard')->with(['members' => $members, 'membersAssigned' => $membersAssigned, 'teams' => $teams, 'project_id' => $project_id, 'list' => $list]);
    }
    return $view;
  }

  public function searchTasksName(Request $request)
  {
    if (!Auth::check()) return redirect('/login');

    $name = '%' . $request->input('name') . '%';
    $id =  $request->input('id');
    $list_id =  $request->input('list_id');
    if (!is_numeric($list_id)||!is_numeric($id))return redirect('/home');

    $project = Project::find($id);
    $this->authorize('isMember', $project);

    $tasks = Task::whereId_list($list_id)->where('name', 'ILIKE', $name)->get();
    $members_through_team = User::join('member', 'member.id_profile', '=', 'profile.id')->join('team_member', 'team_member.id_member', '=', 'member.id')
      ->join('assigned', 'assigned.id_team', '=', 'team_member.id_team')->select('profile.username')->where('assigned.id_list', $list_id)->distinct('profile.username')->get();

    $members_through_team_array = array();
    foreach ($members_through_team as $member) {
      array_push($members_through_team_array, $member->username);
    }
    $members_added_singularly = User::join('member', 'member.id_profile', '=', 'profile.id')
      ->join('assigned', 'assigned.id_member', '=', 'member.id')->select('profile.username')->where('assigned.id_list', $list_id)->distinct('profile.username')->get();

    $members_added_singularly_array = array();
    foreach ($members_added_singularly as $member) {
      array_push($members_added_singularly_array, $member->username);
    }

    $possible_solved_by = array_unique(array_merge($members_through_team_array, $members_added_singularly_array), SORT_REGULAR);

    $view = '';
    foreach ($tasks as $task) {
      $view = $view . view('partials.task', ['task' => $task, 'members_through_team' => $members_through_team, 'project_id' => $id, 'members_added_singularly' => $members_added_singularly, 'possible_solved_by' => $possible_solved_by]);
    }

    return $view;
  }

  public function searchTasksCategory(Request $request)
  {
    if (!Auth::check()) return redirect('/login');

    $name = '%' . $request->input('name') . '%';
    $id =  $request->input('id');
    $list_id =  $request->input('list_id');
    if (!is_numeric($list_id)||!is_numeric($id))return redirect('/home');

    $project = Project::find($id);
    $this->authorize('isMember', $project);

    $tasks = Task::whereId_list($list_id)->where('category', 'ILIKE', $name)->get();
    $members_through_team = User::join('member', 'member.id_profile', '=', 'profile.id')->join('team_member', 'team_member.id_member', '=', 'member.id')
      ->join('assigned', 'assigned.id_team', '=', 'team_member.id_team')->select('profile.username')->where('assigned.id_list', $list_id)->distinct('profile.username')->get();

    $members_through_team_array = array();
    foreach ($members_through_team as $member) {
      array_push($members_through_team_array, $member->username);
    }
    $members_added_singularly = User::join('member', 'member.id_profile', '=', 'profile.id')
      ->join('assigned', 'assigned.id_member', '=', 'member.id')->select('profile.username')->where('assigned.id_list', $list_id)->distinct('profile.username')->get();

    $members_added_singularly_array = array();
    foreach ($members_added_singularly as $member) {
      array_push($members_added_singularly_array, $member->username);
    }

    $possible_solved_by = array_unique(array_merge($members_through_team_array, $members_added_singularly_array), SORT_REGULAR);

    $view = '';
    foreach ($tasks as $task) {
      $view = $view . view('partials.task', ['task' => $task, 'members_through_team' => $members_through_team, 'project_id' => $id, 'members_added_singularly' => $members_added_singularly, 'possible_solved_by' => $possible_solved_by]);
    }

    return $view;
  }
}
