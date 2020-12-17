<?php if ($comment->author != null) $profile = \App\User::find($comment->author);
else {$profile = new \App\User;$profile->image="EmptyUserPicture.jpg";
  $profile->id=0;$profile->email="[deleted user]";$profile->username="[deleted user]";}?>
<div id="comment{{$comment->id}}" class="col-12">
  <div class="card tabMem">
    <div class="card-body">
      <div class="row">
        <div class="col-12 col-md-6" style="margin-bottom: 0.75rem;">
          <button type="button" class="btn" data-toggle="tooltip" data-html="true" title="{{$profile->email}}" onClick="location.href='{{ route('user', 0)}}'">
            <img src={{ asset('profile_images/' . ($profile->image)) }} class="rounded-circle z-depth-1-half avatar-pic" style="width: 23px; margin-right: 5px;" alt="example placeholder avatar" /> </button>
          {{ $comment->text }}
          <p style="margin: 0px;"> @<b>{{ $profile->username }}</b></p>
        </div>
        @can('isAdmin', \App\Project::find($project_id))
        <div class="col-12 col-md-6 d-flex jus-content align-items-up">
          <form>
            <button type="button" class="btn btn-dark" onclick="removeComment({{$comment->id}})">
              <i class="fas fa-minus-circle"></i> Remove Comment
            </button>
          </form>
        </div>
        @endcan
      </div>
    </div>
  </div>
</div>