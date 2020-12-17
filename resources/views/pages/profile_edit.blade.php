@extends('layouts.layoutnotitle')

@section('page_title', 'Edit Profile')

@section('content')
<!-- 
@if (count($errors) != 0)
<div class="col-12 alert alert-danger alert-dismissible fade show" role="alert">
  Something went wrong on
  @foreach ($errors as $error)
    @if ($loop->last)
    <strong>{{$error}}.</strong>
    @break
    @endif
    <strong>{{$error}}, </strong>
  @endforeach
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
@endif -->

<div id="password_change_alert">

</div>

<form method="POST" enctype="multipart/form-data" action="{{ route('editProfile', $profile->id)}}">
  {{ csrf_field() }}
  <div class="row">
    <div class="col-12 col-sm-3">
      <div class="d-flex justify-content-center">
        <img src={{ asset('profile_images/' . ($profile->image)) }} class="rounded-circle z-depth-1-half avatar-pic imgPhoto" alt="example placeholder avatar" />
      </div>
      <div class="d-flex justify-content-center">
        <div class="col-6 btn-text-photo">
          <span class="textOut-b">Photo</span>
          <input id="imagefile" class="text-muted" type="file" name="imagefile" style="display:none" />
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
      <label class="textOut-b" for="exampleInputName">Name</label>
      <input type="text" name="name" class="form-control" id="exampleInputName" value="{{ $profile->name }}" />
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
      <label class="textOut-b">Email</label>
      <input type="email" name="email" class="form-control" value="{{ $profile->email }}" />
      @if ($errors->has('email'))
      <span class="error">
        {{ $errors->first('email') }}
      </span>
      @endif
    </div>

    <div class="col-0 col-lg-3"></div>
  </div>
  <div class="row">
    <div class="col-0 col-sm-3"></div>

    <div class="col-12 col-sm-9 col-lg-6 form-group">
      <label class="textOut-b">Username</label>
      <input type="text" name="username" class="form-control" value="{{ $profile->username }}" />
      @if ($errors->has('username'))
      <span class="error">
        {{ $errors->first('username') }}
      </span>
      @endif
    </div>

    <div class="col-0 col-lg-3"></div>
  </div>
  <div class="row">
    <div class="col-0 col-sm-3"></div>

    <div class="col-12 col-sm-9 col-lg-6 form-group d-flex flex-column">
      <label class="textOut-b" for="passbtn">Password</label>
      <button type="button" class="btn btn-dark" id="passbtn" data-toggle="modal" data-target="#modal_pass">
        <i class="fas fa-key"></i> Change Password
      </button>
      @include('partials.modalchangepass')
    </div>

    <div class="col-0 col-lg-3"></div>
  </div>
  <div class="row">
    <div class="col-0 col-sm-3"></div>

    <div class="col-12 col-sm-9 col-lg-6 form-group">
    </div>

    <div class="col-0 col-lg-3"></div>
  </div>
  <div class="row">
    <div class="col-0 col-sm-3"></div>
    <div class="col-12 col-sm-9 col-lg-6 div-btn-create-cancel">
      <button type="submit" class="btn btn-secondary">
        Save Changes
      </button>
      <button type="button" onclick="location.href='{{ route('user', $profile->id)}}'" class="btn btn-outline-dark">
        Cancel
      </button>
    </div>
    <div class="col-0 col-lg-3"></div>
  </div>
</form>
@endsection