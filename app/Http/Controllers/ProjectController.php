<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Project;
use App\Member;

class ProjectController extends Controller
{

  public function create(Request $request)
  {
    if (!Auth::check()) return redirect('/login');

    $validatedData = $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'nullable|string|max:4000',
      'imagefile' => 'nullable|max:10000',
    ]);
    $project = new Project();

    $project->name = $request->input('name');
    $project->description = $request->input('description');
    $project->creator = Auth::user()->id;
    $project->save();
    $id=$project->id;
    if ($request['imagefile'] != NULL) {
      $image = $request->file("imagefile");
      $projectImageName = "projectImage-" . $id . ".png";
      $image->move('project_images/', $projectImageName);
      $project->image = $projectImageName;
      $project->save();
    }

    $member = new Member();
    $member->id_project = $id;
    $member->id_profile = Auth::id();
    $member->coordinator = true;
    $member->save();
    if ($request['members'] != NULL) {
      foreach ($request['members'] as $username) {
        $newMember = new Member();
        $newMember->id_project = $id;
        $newMember->id_profile = \App\User::where('username', $username)->get('id')->first()->id;
        $newMember->coordinator = false;
        $newMember->save();
      }
    }

    return redirect('home');
  }

  public function show_project($id)
  {
    if (!Auth::check()) return redirect('/login');
    if (!is_numeric($id))return redirect('/home');

    $project = Project::find($id);

    $this->authorize('isMember', $project);

    $project_id = $project->id;

    $tasks_count = DB::transaction(function () use ($project_id) {
      return DB::select('
    select getCompletedProjectTasks(project.id) as completed, getUncompletedProjectTasks(project.id) as uncompleted
    from project
    where project.id = ?', [$project_id]);
    });

    $admins=\App\User::join('member', 'profile.id','=','member.id_profile')->select('profile.image')->whereId_project($id)->whereCoordinator(true)->get();
    return view('pages.project', ['project' => $project, 'tasks_count' => $tasks_count[0], 'admins'=>$admins]);
  }

  public function show_edit_project($id)
  {
    if (!Auth::check()) return redirect('/login');
    if (!is_numeric($id))return redirect('/home');

    $project = Project::find($id);

    $this->authorize('isAdmin', $project);

    return view('pages.project_edit')->with('project', $project);
  }

  public function show_project_form()
  {
    if (!Auth::check()) return redirect('/login');

    $profiles = \App\User::where('id','<>',Auth::id())->distinct()->get();

    return view('pages.project_form')->with('profiles', $profiles);
  }

  public function edit(Request $request, $id)
  {
    if (!Auth::check()) return redirect('/login');
    if (!is_numeric($id))return redirect('/home');
    $project = Project::find($id);

    $this->authorize('isAdmin', $project);
    
    $validatedData = $request->validate([
      'name' => 'required|string|max:255',
      'description' => 'nullable|string|max:4000',
      'imagefile' => 'nullable|max:10000',
    ]);
    
    $project->name = $request['name'];
    $project->description = $request['description'];

    if ($request['imagefile'] != NULL) {
      $image = $request->file("imagefile");
      $projectImageName = "projectImage-" . $id . ".png";
      $image->move('project_images/', $projectImageName);
      $project->image = $projectImageName;
    }

    $project->save();

    return redirect('projects/' . $id)->with(['project'=> $project,'has_saved'=> "true"]);
  }
}
