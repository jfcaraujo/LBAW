<?php if ($answer->author != null) $profile=\App\User::find($answer->author);
else {$profile = new \App\User;$profile->image="EmptyUserPicture.jpg";
$profile->id=0;$profile->email="[deleted user]";$profile->username="[deleted user]";}?>
<div class="col-0 col-md-1"></div>
<div class="col-12 col-md-10">
    <div class="card tabMem">
        <div class="card-body">
            <div class="row">
                <div class="col-12 col-md-6" style="margin-bottom: 0.75rem;">
                    <button type="button" class="btn" data-toggle="tooltip" data-html="true" title="{{$profile->email}}" onClick="location.href='{{ route('user', $profile->id)}}'">
                        <img src={{ asset('profile_images/' . ($profile->image)) }}  class="rounded-circle z-depth-1-half avatar-pic" style="width: 23px; margin-right: 5px;" alt="example placeholder avatar" />
                        </button>
                        {{ $answer->text }}</h7>
                        <p style="margin: 0px;"> @<b>{{ $profile->username }}</b></p>
                </div>

                <div class="col-12 col-md-6 d-flex jus-content align-items-end">
                    @can('isAdmin', \App\Project::find($id))
                    <button type="button" class="btn btn-dark" onclick="deleteAnswer(this, {{ $answer->id }},{{$id}})">
                        <i class="fas fa-minus-circle"></i> Remove Answer
                    </button>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-0 col-md-1"></div>
<!-- END A -->