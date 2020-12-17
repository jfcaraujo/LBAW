<?php $answers=\App\Forum_comment::whereId_question($question->id)->get();
if ($question->author != null) $profile = \App\User::find($question->author);
else {$profile = new \App\User;$profile->image="EmptyUserPicture.jpg";
    $profile->id=0;$profile->email="[deleted user]";$profile->username="[deleted user]";}?>
<div class="row">
    <div class="col-12 mt-1">
        <div class="card tabMem">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-md-6">
                        <button type="button" class="btn" data-toggle="tooltip" title="{{$profile->email}}" onClick="location.href='{{ route('user', $profile->id)}}'">
                        <img src={{ asset('profile_images/' . ($profile->image)) }}  class="rounded-circle z-depth-1-half avatar-pic" style="width: 23px; margin-right: 5px;" alt="example placeholder avatar" />
                        </button>
                            {{ $question->text }}
                        <p style="margin: 0px;"> @<b>{{ $profile->username }}</b></p>
                    </div>

                    <div class="col-12 col-md-6 d-flex jus-content align-items-end">
                        <button id="numberOfQuestionId{{$question->id}}" type="button" class="btn btn-outline-dark" style="margin-right: 10px;" onclick="forumDropdown(this)">
                            {{ count($answers) }}
                        </button>
                        <!-- for not admin just remove this btn -->
                        @can('isAdmin', \App\Project::find($question->id_project))
                        <button type="button" class="btn btn-dark" onclick="deleteQuestion(this, {{ $question->id }}, {{$question->id_project}})">
                            <i class="fas fa-minus-circle"></i> Remove Question
                        </button>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="answersDiv" class="row" style="display: none;">
@foreach ($answers as $answer)
    @include('partials.answer', [$answer, 'id'=>$question->id_project])
@endforeach
    <!-- My answer -->
    <div id="addAnswerForQuestionId{{$question->id}}" class="col-0 col-md-1"></div>
    <div class="col-12 col-sm-8">
        <textarea class="form-control" id="exampleFormControlTextarea1" rows="2" style="border-color: black;" placeholder="Insert..." maxlength="500"></textarea>
    </div>
    <div class="col-12 col-sm-2 d-flex align-items-end padd-on-off">
        <div class="d-inline p-1">
            <button type="button" class="btn btn-dark btn-sm" onclick="addAnswer(this,{{ $question->id }}, {{$question->id_project}})">
                <i class="fas fa-paper-plane"></i>
            </button>
        </div>
    </div>
    <div class="col-0 col-md-1"></div>
    <!-- END MY ANSWER -->
</div>