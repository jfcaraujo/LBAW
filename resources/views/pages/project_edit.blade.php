@extends('layouts.layoutnotitle')

@section('page_title', 'Edit Project')

@section('content')

<form method="POST" enctype="multipart/form-data" action="{{ route('projects/edit', $project->id)}}">
  {{ csrf_field() }}
  <div class="row">
    <div class="col-12 col-sm-3">
      <div class="d-flex justify-content-center">
      <?php $url="project_images/" . ($project->image)?>
        <div class="z-depth-1-half avatar-pic imgPhoto" style="background-image: url('{{ asset("$url") }}'); height: 50px; background-repeat:no-repeat;
background-position: center center;">
        </div>
      </div>
      <div class="d-flex justify-content-center">
        <div class="col-6 btn-text-photo">
          <span class="textOut-b">Photo</span>
          <input id="imagefile" name="imagefile" style="display:none" class="text-muted" type="file" />
          <input type="button" value="Upload" onclick="document.getElementById('imagefile').click();" />
          @if ($errors->has('imagefile'))
          <span class="error">
            {{ $errors->first('imagefile') }}
          </span>
          @endif
        </div>
      </div>
    </div>

    <div class="col-12 col-sm-9 col-lg-6 form-group">
      <label class="textOut-b" for="exampleInputName">Project Name</label>
      <input type="text" name="name" class="form-control" id="exampleInputName" value="{{ $project->name }}" />
      @if ($errors->has('name'))
          <span class="error">
            {{ $errors->first('name') }}
          </span>
      @endif
    </div>

    <div class="col-0 col-lg-3"></div>
  </div>

  <div class="row">
    <div class="col-0 col-sm-3"></div>

    <div class="col-12 col-sm-9 col-lg-6 form-group">
      <label class="textOut-b" for="exampleFormControlTextarea1">Description</label>
      <textarea class="form-control" name="description" id="exampleFormControlTextarea1" rows="3">{{ $project->description }}</textarea>
      @if ($errors->has('description'))
          <span class="error">
            {{ $errors->first('description') }}
          </span>
          @endif
    </div>

    <div class="col-0 col-lg-3"></div>
  </div>

  <div class="row">
    <div class="col-0 col-sm-3"></div>
    <div class="col-12 col-sm-9 col-lg-6 div-btn-create-cancel">
      <button type="submit" class="btn btn-secondary">
        Save Changes
      </button>
      <button type="button" onclick="location.href='{{ route('projectAbout', $project->id)}}'" class="btn btn-outline-dark">
        Cancel
      </button>
    </div>
    <div class="col-0 col-lg-3"></div>
  </div>
</form>
@endsection