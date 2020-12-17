<?php  $profile = \App\User::find($member->id_profile);   ?>
<div class="col-12 mt-1">
    <div class="card tabMem">
      <div class="card-body">
       
       <div class="row">
        <div class="col-9 card-title d-flex flex-row">
          <img
          src={{ asset('profile_images/' . ($profile->image)) }}
            class="rounded-circle z-depth-1-half avatar-pic new-user-image"
            alt="example placeholder avatar"
          />
          <h5>{{ $profile->name }}</h5>
        </div>

        <div
            class="col-3 card-title dropdown d-flex justify-content-end"
          >
          @can('isAdmin', \App\Project::find($member->id_project))
            <button class="btn dropleft" style="padding-top: 0px" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-ellipsis-v"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-right">
            @if ($member->coordinator)
            <a class="dropdown-item" href="{{ route('deleteMember', [$member->id_project, $member->id])}}">Delete</a>
            @else
              <a class="dropdown-item"
                 data-toggle="tooltip"
                 data-placement="top"
                 href="{{ route('upgradeMember', [$member->id_project, $member->id])}}"
                 title="An Admin can manage the project (create and delete tasks, ...). After making a user Admin, you cannot unmake it.">
                  Make Admin</a>
              <div class="dropdown-divider"></div>
              <a class="dropdown-item" href="{{ route('deleteMember', [$member->id_project, $member->id])}}">Delete</a>
            @endif
            </div>
          @endcan
                </div>
        </div>
        <div class="d-flex flex-row ml-4">
          <p>{{ $profile->username }}</p>
          @if ($member->coordinator)
          <p>, (Admin)</p>
          @endif
        </div>
      </div>
    </div>
  </div>