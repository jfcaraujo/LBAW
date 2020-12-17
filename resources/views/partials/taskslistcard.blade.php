<div id="taskList{{$list->id}}" class="col-12 col-md-6 col-xl-4 padd-col-4">
    <div class="card tabTask">
      <div class="card-body cardTask">
        <div class="row card-title">
          <div class="col-9" style="font-size: 1.25rem; font-weight: 500;
    line-height: 1.2;">
            <a id="taskListName{{$list->id}}" class="button a-black" href="{{ route('projectTasks', ['id' => $project_id, 'list_id' => $list->id]) }}"> {{ $list->name }} </a>
          </div>
          <div class="col-3 dropdown d-flex justify-content-end">
            @can('isAdmin', \App\Project::find($project_id))
            <button class="btn dropleft" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-ellipsis-v"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-right">
              <a class="dropdown-item" data-toggle="modal" data-target="#modalEdit-{{$list->id}}">Edit</a>
              <div class="dropdown-divider"></div>
                  <a class="dropdown-item" data-toggle="modal" data-target="#modalListDeleteConfirm-{{$list->id}}">Delete</a>
              </div> 
            @endcan
          </div> 
         
        </div>
        <div class="row">
          <div class="col-4">
            <p style="font-size: 14px;">total tasks:<br /> {{ $list->total_tasks }} </p>
          </div>
          <div class="col-4">
            <p style="font-size: 14px;">done:<br />{{ $list->completed }} </p>
          </div>
          <div class="col-4">
            <p style="font-size: 14px;">not done:<br /> {{ $list->uncompleted }} </p>
          </div>
          <div class="col-12">
            <p>Assigned to:</p>
          </div>
          <div class="col-12 d-flex flex-row">
          <?php $i = 0;?>
            @foreach($membersAssigned as $user)
              @if ($i < 4)  
              <?php $profile=\App\User::whereUsername($user)->get()[0];?>
                <img  src={{ asset('profile_images/' . ($profile->image)) }}
                      class="rounded-circle z-depth-1-half avatar-pic new-user-image"
                      alt="example placeholder avatar"  />               
              @endif             
              <?php $i++; ?>
              @endforeach
            @can('isAdmin', \App\Project::find($project_id))
            <button
              type="button"
              class="btn btn-secondary small-btn"
              style="margin-left: 10px;"
              data-toggle="modal"
              data-target="#modalMembers-{{ $list->id }}"
            >
              <i class="fas fa-plus"></i>
            </button>
            @include('partials.modalmembers')
            @include('partials.modalname', ['list_id' => $list->id])
            @include('partials.modalListDeleteConfirm', ['list_id' => $list->id])
            @endcan
          </div>
        </div>
      </div>
    </div>
  </div>