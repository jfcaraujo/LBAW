
@extends('layouts.layoutextra1', ['id' => $project->id, 'completed' => $tasks_count->completed, 'uncompleted' => $tasks_count->uncompleted])

@section('page_title', "$project->name")

@section('title', "$project->name")

@section('content')
@if (session("has_saved"))
<div class="col-12 alert alert-success alert-dismissible fade show" role="alert">
  Project <strong>successfully updated.</strong> 
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
@endif
<div class="row">
  <div class="col-12">
    <p>
      <b> Created: </b> {{ date("d-m-Y", strtotime($project->created)) }}
    </p>
  </div>
  <div class="col-12 d-flex flex-row">
    <p class="mr-3"> <b> Admins: </b> </p>
    @foreach($admins as $admin)
    <?php $url="profile_images/" . ($admin->image)?>
    <div class="image-user mr-1" style="background-image: url('{{ asset("$url") }}');"></div>
    @endforeach
  </div>
  <div class="col-12">
    <hr class="my-4" />
  </div>
  <div class="col-lg-2 col-md-3 col-sm-4 col-12 d-flex jus-on-project">
    <?php $url="project_images/" . ($project->image)?>
    <div class="imageProj" style="background-image: url('{{ asset("$url") }}'); width: 180px; height: 180px;"></div>
  </div>
  <div class=" col-lg-10 col-md-9 col-sm-8 col-12 d-flex jus-on-project">
    <p>
      {{$project->description}}
    </p>
  </div>
  <div class=" col-12 mt-5">
    <h2>Statistics</h2>
    <hr class="my-4" />
    <h4>Tasks in the project</h4>
    <div>
      <canvas id="pieChart"></canvas>
    </div>
  </div>
</div>
@endsection
