<div id="taskCard{{$task->id}}" class="col-12 col-md-6 col-xl-4 padd-col-4">
  <div class="card tabTask">
    @if(!is_null( $task->solved ))
    <div class="imageTaskSolved"></div>
    @else
    <div class="imageTaskUnsolved"></div>
    @endif
    <div class="card-body cardTask">
      <h5 class="row card-title">
        <div class="col-9">
          <a id="taskCardName{{$task->id}}" class="button a-black" href="{{ route('task', ['project_id' => $project_id, 'task_id' => $task->id]) }}"> {{ $task->name }} </a>
        </div>
        <div class="col-3 dropdown d-flex justify-content-end">
          @can('isAdmin', \App\Project::find($project_id))
          <button class="btn dropleft" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="fas fa-ellipsis-v"></i>
          </button>
          <div class="dropdown-menu dropdown-menu-right">
            <a class="dropdown-item" data-toggle="modal" data-target="#modalTaskEdit-{{ $task->id }}">Edit</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" data-toggle="modal" data-target="#modalTaskDeleteConfirm-{{$task->id}}">Delete</a>
          </div>
          @endcan
        </div>
      </h5>
      <p> <i id="taskCardCategory{{$task->id}}">{{ $task->category }}</i> </p>
      <div class="row">
        <div class="col-6">
          <p style="font-size: 14px;">created:<br /> {{ date("d-m-Y", strtotime($task->created)) }} </p>
          <?php if ($task->creator != null) {
            $user = \App\User::find($task->creator);
            $url = "profile_images/" . ($user->image);
          } else $url = "profile_images/EmptyUserPicture"; ?>
          <div class="image-user" style="background-image: url('{{ asset("$url") }}');"></div>
        </div>
        <div class="col-6">
          <p style="font-size: 14px;">solved:<br />
            @if(!is_null( $task->solved ))
            {{ date("d-m-Y", strtotime($task->solved)) }}
            @else -
            @endif
          </p>
          @if(is_null( $task->solved ))
          @can('isAdmin', \App\Project::find($project_id))
          <button type="button" class="btn btn-secondary small-btn" data-toggle="modal" data-target="#modalSolvedBy-{{ $task->id }}">
            <i class="fas fa-plus"></i>
          </button>
          @endcan
          @else
          <?php $user = \App\User::find($task->solver);
          if ($user != null) $url = "profile_images/" . ($user->image);
          else $url = "profile_images/EmptyUserPicture.jpg"; ?>
          <div class="image-user" style="background-image: url('{{ asset("$url") }}');"></div>
          @endif
        </div>
      </div>
      <!-- Modal -->
      @include('partials.modal_solved_by', ['task_id' => $task->id])
      @can('isAdmin', \App\Project::find($project_id))
      @include('partials.modaltaskedit', ['task_id' => $task->id])
      @include('partials.modalTaskDeleteConfirm', ['task_id' => $task->id])
      @endcan
    </div>
  </div>
</div>