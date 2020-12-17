@extends('layouts.layout2', ['project_id' => $project->id])

@section('page_title', 'Members')

@section('title', 'Members & Teams')

@section('content')

<div class="row searchFilter">
  <?php $id = $project->id ?>
  <input type="hidden" id="projectId" value="{{$id}}"  >
  <div class="col-sm-12 col-md-6" id="memberSearchbar">
  @include('partials.searchbar', ['options' => Array('all', 'teams', 'members')])
  </div>

  <div class="col-3 col-md-0"></div>
  <div class="col-md-3 col-12"></div>
</div>

<div class="row">
  <div class="col-6"> 
    <h3>Teams</h3>
  </div>
  <div class="col-6 d-flex justify-content-end">
    @can('isAdmin', $project)
    <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#modalCreateTeam">
      <i class="fas fa-plus-circle"></i> Add Team
    </button>
    @include('partials.modalcreateteam')
    @endcan
  </div>

  <div id="addTeamsNext" class="col-12">
    <hr class="my-4" />
  </div>
  
  <?php for($i = 0; $i < sizeof($teams); $i++) {  ?>
    @include('partials.team', ['team' => $teams[$i], 'profilesTeamMembers' => $profilesTeamMembersArray[$i], 'profilesNotInTeam' => $profilesNotInTeamArray[$i]])
  <?php } ?>
</div>

<div class="row" style="margin-top: 1.25rem;">
  <div class="col-12 col-sm-6">
    <h3>Members</h3>
  </div>

  <div class="col-12 col-sm-6 d-flex jus-content-members">
    @can('isAdmin', $project)
    <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#modalJustMembers">
      <i class="fas fa-plus-circle"></i> Add Members
    </button>
    @include('partials.modaljustmembers', ['profiles' => $profilesNotInProject])
    @endcan
  </div>
  
  <div id="addMembersNext" class="col-12">
    <hr class="my-4" />
  </div>

  @each('partials.member', $members, 'member') 
   
</div>
@endsection
