<div class="col-12 col-lg-6 mt-1 mb-1">
  <div class="card tabProj">
    <?php $id = $project->id;
    $url = "project_images/" . ($project->image) ?>
    <a class="imageProj" style="background-image: url('{{ asset("$url") }}') ;" href="{{ route('projectAbout', $id)}}"></a>
    <div class="card-body car-padd-top">
      <a class="card-title" style="color: black; font-size: x-large; text-decoration: none;" href="{{ route('projectAbout', $id)}}"> {{ $project->name }} </a>
      <div class="progress progBar">
        <div class="progress-bar" role="progressbar" style="width: {{ $projects_completion[$project->id] }}%;" aria-valuenow="{{ $projects_completion[$project->id] }}" aria-valuemin="0" aria-valuemax="100">
          {{ $projects_completion[$project->id] }}%
        </div>
      </div><?php $admin = \App\Member::whereId_profile(\Illuminate\Support\Facades\Auth::id())->whereId_project($project->id)->get()[0]->coordinator; ?>
      <h6 style="padding-top: 10px;">
        @if($admin)
        Admin
        @endif</h6>
    </div>
  </div>
</div>