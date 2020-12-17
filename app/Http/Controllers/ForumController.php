<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Project;
use App\Forum_question;
use App\Forum_comment;


class ForumController extends Controller
{
  public function show_forum($id)
  {
    if (!Auth::check()) return redirect('/login');
    if (!is_numeric($id))return redirect('/home');
    $project = Project::find($id);
    $this->authorize('isMember', $project);
    
    $questions = Forum_question::whereId_project($id)->get();
    return view('pages.forum')->with(['id' => $id, 'questions' => $questions]);
  }

  function search(Request $request){
    if (!Auth::check()) return redirect('/login');
    if (!is_numeric($request['idProject']))return redirect('/home');
    $project = Project::find($request['idProject']);
    $this->authorize('isMember',$project);
    $name=$request['name'];
    $questions = Forum_question::whereId_project($request['idProject'])->where("text","ILIKE","%$name%")->get();
    $view='';
    foreach($questions as $question){
        $view = $view . view('partials.question', ['question' => $question]);
    }
    return $view;
  }

  function addQuestion(Request $request)
  {
    if (!Auth::check()) return redirect('/login');
    if (!is_numeric($request['idProject']))return redirect('/home');
    $project = Project::find($request['idProject']);
    $this->authorize('isMember',$project);

    $question = new Forum_question();
    $question->text = $request['text'];
    $question->id_project = $request['idProject'];
    $question->author = Auth::id();
    $question->save();
    return view('partials.question')->with(['question' => $question]);
  }

  function addAnswer(Request $request)
  {
    if (!Auth::check()) return redirect('/login');
    if (!is_numeric($request['idProject']) || !is_numeric($request['idQuestion']))return redirect('/home');
    $project = Project::find($request['idProject']);
    $this->authorize('isMember',$project);

    $answer = new Forum_comment();
    $answer->text = $request['text'];
    $answer->id_question = $request['idQuestion'];
    $answer->author = Auth::id();
    $answer->save();
    $view = view('partials.answer')->with(['answer' => $answer,'id'=>$request['idProject']]);
    $idQuestion = $request['idQuestion'];
    return array("$view", "$idQuestion");
  }

  function deleteQuestion(Request $request)
  {
    if (!Auth::check()) return redirect('/login');
    if (!is_numeric($request['idProject']) || !is_numeric($request['idQuestion']))return redirect('/home');
    $project = Project::find($request['idProject']);
    $this->authorize('isAdmin',$project);

    $question = Forum_question::find($request['idQuestion']);
    $question->delete();
    return 1;
  }

  function deleteAnswer(Request $request)
  {
    if (!Auth::check()) return redirect('/login');
    if (!is_numeric($request['idProject']) || !is_numeric($request['idAnswer']))return redirect('/home');
    $project = Project::find($request['idProject']);
    $this->authorize('isAdmin',$project);

    $answer = Forum_comment::find($request['idAnswer']);
    $answer->delete();
    return 2;
  }
}
