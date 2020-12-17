<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Project;

class HomeController extends Controller
{

	public function show_home()
	{
		if (!Auth::check()) return redirect('/login');

		$projects = Project::join('member', 'member.id_project', '=', 'project.id')->select('project.*')->where('member.id_profile', Auth::id())->get();

		$projects_completion = array();

		foreach ($projects as $project) {

			$project_id = $project->id;
			
			$tasks_count = DB::transaction(function () use ($project_id) {
				return DB::select('
			select getCompletedProjectTasks(project.id) as completed, getUncompletedProjectTasks(project.id) as uncompleted
			from project
			where project.id = ?', [$project_id]);
			});

			$completed_count = $tasks_count[0]->completed;
			$uncompleted_count = $tasks_count[0]->uncompleted;
			$total_count = $completed_count + $uncompleted_count;
			if ($total_count == 0) {
				$percentage = 100;
			} else {
				$percentage = round($completed_count / $total_count * 100);
			}

			$projects_completion[$project->id] = $percentage;
		}

		return view('pages.home', ['projects_completion' => $projects_completion, 'projects' => $projects]);
	}



	public function search(Request $request)
	{
        if (!Auth::check()) return redirect('/login');

		$name = $request->input('name');

		if ($name=="")$projects = Project::join('member', 'member.id_project', '=', 'project.id')->select('project.*')->where('member.id_profile', Auth::id())->get();
		else 
		  $projects=DB::table('project')->join('member', 'member.id_project', '=', 'project.id')->select("project.*")
		 ->whereRaw("(to_tsvector('english',project.name)||to_tsvector('english',coalesce(project.description,''))) @@ plainto_tsquery('english',?)",[$name])->where("member.id_profile",Auth::id())
		->orderByRaw("ts_rank((setweight(to_tsvector('english',project.name),'A')||setweight(to_tsvector('english',coalesce(project.description,'')),'B')), plainto_tsquery('english',?)) DESC",[$name]) ->get(); 
		 
		$projects_completion = array();
		foreach ($projects as $project) {
			$project_id = $project->id;

			$tasks_count = DB::transaction(function () use ($project_id) {
				return DB::select('
			select getCompletedProjectTasks(project.id) as completed, getUncompletedProjectTasks(project.id) as uncompleted
			from project
			where project.id = ?', [$project_id]);
			});

			$completed_count = $tasks_count[0]->completed;
			$uncompleted_count = $tasks_count[0]->uncompleted;
			$total_count = $completed_count + $uncompleted_count;
			if ($total_count == 0) {
				$percentage = 100;
			} else {
				$percentage = round($completed_count / $total_count * 100);
			}
			$projects_completion[$project->id] = $percentage;
		}

		$view = '';
		foreach ($projects as $project) {
			$view = $view . view('partials.project', ['projects_completion' => $projects_completion, 'project' => $project]);
		}
		return $view;
	}
}
