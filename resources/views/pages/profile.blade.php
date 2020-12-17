@extends('layouts.layoutnotitle') @section('page_title', 'Profile')
@section('content')

<div class="row">

@if (session("has_saved"))
<div class="col-12 alert alert-success alert-dismissible fade show" role="alert">
  Profile <strong>successfully updated.</strong> 
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
@endif
  <div class="col-0 col-sm-3"></div>

  <div class="col-12 col-sm-9 col-lg-6 form-group align-on-right">
    @can('edit',$profile)
    <button onclick="location.href='{{$profile->id}}/edit'" type="button" class="btn btn-dark">
      <i class="fas fa-edit"></i> Edit
    </button>
    @endcan
  </div>

  <div class="col-0 col-lg-3"></div>
</div>
<div class="row">
  <div class="col-12 col-sm-3">
    <div class="d-flex justify-content-center">
      <img
        src={{ asset('profile_images/' . ($profile->image)) }}
        class="rounded-circle z-depth-1-half avatar-pic"
        style="width: 80px;"
        alt="user image"
      />
    </div>
  </div>

  <div class="col-12 col-sm-9 col-lg-6 form-group">
    <label class="textOut-b">Name</label>
    <h5>{{ $profile->name }}</h5>
  </div>

  <div class="col-0 col-lg-3"></div>
</div>
<div class="row">
  <div class="col-0 col-sm-3"></div>

  <div class="col-12 col-sm-9 col-lg-6 form-group">
    <label class="textOut-b">Email</label>
    <h5>{{ $profile->email }}</h5>
  </div>

  <div class="col-0 col-lg-3"></div>
</div>
<div class="row">
  <div class="col-0 col-sm-3"></div>
  <div class="col-12 col-sm-9 col-lg-6 form-group">
    <label class="textOut-b">Username</label>
    <h5>{{ $profile->username }}</h5>
  </div>
  <div class="col-0 col-lg-3"></div>
</div>

<div class="row">
  <div class="col-0 col-sm-3"></div>
  <div class="col-12 col-sm-9 col-lg-6 form-group">
    <label class="textOut-b">Password</label>
    <h5>***********</h5>
  </div>
  <div class="col-0 col-lg-3"></div>
</div>
@if (Auth::user()->can('edit', $profile));
<div class="row">
  <div class="col-0 col-sm-3"></div>

  <div class="col-12 col-sm-9 col-lg-6 form-group align-on-right">
    <?php $id = Auth::user()->id ?>
      <a class="a-bw btn btn-outline-dark" href="{{ route('deleteUser', $id)}}" role="button">Delete account</a>
  </div>

  <div class="col-0 col-lg-3"></div>
</div>
@endif
@endsection
