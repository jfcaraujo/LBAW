@extends('layouts.layout2', ['project_id' => $project_id])
@section('page_title', 'Lists of Tasks')
@section('title', 'Lists of Tasks')

@section('content')
<div class="row searchFilter">
  <div class="col-sm-12 col-md-6" id="taksListSearchbar">
    <input type="hidden" id="projectId" value="<?php echo $project_id;?>"  >
    @include('partials.searchbarnofilter')
  </div>

  <div class="col-3 col-md-0"></div>
  <div
    class="col-md-3 col-12 d-flex justify-content-end align-items-end pad-to-mobile"
  >
    @can('isAdmin', \App\Project::find($project_id))
    <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#exampleModalCenter">
      <i class="fas fa-plus-circle"></i> Add List
    </button>
    @include('partials.modaltasklist')
    @endcan
  </div>
</div>


<div class="row" >

  <div id="tasksListRow">
  </div>

  @foreach($lists as $list)
    @include('partials.taskslistcard',['membersAssigned'=>$membersArray[$list->id], 'teams'=>$teamsArray[$list->id]])
  @endforeach

</div>
@endsection
