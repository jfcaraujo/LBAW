@extends('layouts.layout2') @section('page_title', $task->name)
@section('title', $task->name) @section('content')
<div class="row searchFilter">
  <div class="col-12 d-flex justify-content-end align-items-end pad-to-mobile">
    @can('isAdmin', \App\Project::find($project_id))
      <a style="color: white; text-decoration: none;" class="button a-black btn btn-dark" href="{{ route('remove_task', ['task_id' => $task->id]) }}" role="button">
        <i class="fas fa-minus-circle">
        </i>
        Remove Task
      </a>
      @endcan
  </div>
</div>

<div class="col-12">
  <div class="col-12">
    <h2>Info</h2>
    <hr class="my-4" />
    <p style="margin: 0px;">
      	<b> Created: </b>
    	{{ date("d-M-Y", strtotime($task->created)) }}
    	 <b>by</b> {{ $creator->username }}
    </p>
    <p style="margin: 0px;">
    @if(!is_null( $task->solved ))
      <b> Solved: </b> 
      {{ date("d-m-Y", strtotime($task->solved)) }}
      <b>by</b> {{ $solver->username }}
    @endif
    </p>
    <p> <b>Category:</b> {{ $task->category}} </p>
    <h4> Description </h4>
    <hr class="my-4" />
    <p>{{ $task->description }}</p>
  </div>
  <div class="col-12">
    <h2>Comments</h2>
    <hr class="my-4" />
  </div>
  <!-- C's -->
  <div class="row">
    <!-- C -->
    @foreach($comments as $comment)
    @include('partials.comment')
    @endforeach
    <!-- END C -->
    <!-- My answer -->
    <div id="addCommentBefore" class="col-12 col-sm-10">
      <textarea class="form-control" id="newComment" rows="2" style="border-color: black;" placeholder="Insert..."></textarea>
    </div>
    <div class="col-12 col-sm-2 d-flex align-items-end padd-on-off">
      <div class="d-inline p-1">
        <button onclick="addComment({{ $task->id }},{{$project_id}})" type="button" class="btn btn-dark btn-sm">
          <i class="fas fa-paper-plane"></i>
        </button>
      </div>
    </div>
    <div class="col-0 col-md-1"></div>
    <!-- END MY ANSWER -->
  </div>
  <!-- END C's -->
</div>
@endsection