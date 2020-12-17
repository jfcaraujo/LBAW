@extends('layouts.layout2', ['project_id' => $id])

@section('page_title', 'Forum')

@section('title', 'Forum')

@section('content')
<div class="row searchFilter">
  <input type="hidden" id="projectId" value="{{$id}}">
  <div class="col-sm-12 col-md-6" id="forumSearchbar">
    @include('partials.searchbarnofilter')
  </div>
  <div class="col-3 col-md-0"></div>
  <div class="col-md-3 col-12 d-flex justify-content-end align-items-end pad-to-mobile">
    <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#modal_question">
      <i class="fas fa-plus-circle"></i> Add Question
    </button>
    <!-- Modal -->
    @include('partials.modalquestion')
  </div>
</div>
<div class="col-12" id="allQuestions">
  @each('partials.question', $questions, 'question')
</div>
@endsection