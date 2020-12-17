@extends('layouts.layout2', ['project_id' => $project_id])

@section('page_title', 'Tasks') 
@section('title', 'Tasks')
@section('content')

<!-- 3 row -->
<div class="row searchFilter">
  <div class="col-sm-12 col-md-6" id="taksSearchbar">
    <input type="hidden" id="taskListId" value="<?php echo $list_id;?>"  >
    <input type="hidden" id="projectId" value="<?php echo $project_id;?>"  >
    @include('partials.searchbar', ['options' => Array('name', 'category')])
  </div>

  <div class="col-3 col-md-0"></div>
  <div
    class="col-md-3 col-12 d-flex justify-content-end align-items-end pad-to-mobile"
  >
    @can('isAdmin', \App\Project::find($project_id))
    <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#modalTask">
      <i class="fas fa-plus-circle"></i> Add Task
    </button>
    @include('partials.modalTask')
    @endcan
  </div>
</div>

<!-- 1 row do container final -->
<div class="row">

  <div id="tasksRow">
  </div>

  @foreach($tasks as $task)
    @include('partials.task')
  @endforeach
</div>
@endsection
