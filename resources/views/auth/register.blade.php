@extends('layouts.layoutsimple') @section('page_title', 'Register')
@section('content')

<form method="POST" enctype="multipart/form-data" action="{{ route('register') }}">
  {{ csrf_field() }}

  <div class="row formLogin">
    <div class="col-2 col-xl-3"></div>
    <div class="file-field col-8 col-xl-6">
      <div class="d-flex justify-content-center">
        <img src={{ asset('profile_images/EmptyUserPicture.jpg' ) }} class="rounded-circle z-depth-1-half avatar-pic imgPhoto" alt="example placeholder avatar" />
      </div>
      <div class="d-flex justify-content-center">
        <div class="col-10 col-xl-6 btn-text-photo">
          <span class="textOut-w">Photo</span>
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

    <div class="col-2 col-xl-3"></div>
    <div class="col-2 col-xl-3"></div>

    <div class="col-8 col-xl-6 form-group">
      <label class="textOut-w" for="name">Name</label>
      <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus class="form-control" aria-describedby="nameHelp" placeholder="Enter name" />

      <small id="nameHelp" class="form-text text-muted">First and Last Name.</small>
      @if ($errors->has('name'))
      <span class="error">
        {{ $errors->first('name') }}
      </span>
      @endif
    </div>

    <div class="col-2 col-xl-3"></div>
    <div class="col-2 col-xl-3"></div>

    <div class="col-8 col-xl-6 form-group">
      <label class="textOut-w" for="email">E-Mail Address</label>
      <input id="email" type="email" name="email" value="{{ old('email') }}" required class="form-control" aria-describedby="emailHelp" placeholder="Enter email" />
      @if ($errors->has('email'))
      <span class="error">
        {{ $errors->first('email') }}
      </span>
      @endif
      <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
    </div>

    <div class="col-2 col-xl-3"></div>
    <div class="col-2 col-xl-3"></div>

    <div class="col-8 col-xl-6 form-group">
      <label class="textOut-w" for="username">Username</label>
      <input id="username" type="text" name="username" value="{{ old('username') }}" required class="form-control" aria-describedby="usernameHelp" placeholder="Username" />
      @if ($errors->has('username'))
      <span class="error">
        {{ $errors->first('username') }}
      </span>
      @endif
      <small id="usernameHelp" class="form-text text-muted">This will be visible for everyone.</small>
    </div>

    <div class="col-2 col-xl-3"></div>
    <div class="col-2 col-xl-3"></div>

    <div class="col-8 col-xl-6 form-group">
      <label class="textOut-w" for="password">Password</label>
      <input id="password" type="password" name="password" required class="form-control" placeholder="Password" />
      @if ($errors->has('password'))
      <span class="error">
        {{ $errors->first('password') }}
      </span>
      @endif
    </div>

    <div class="col-2 col-xl-3"></div>
    <div class="col-2 col-xl-3"></div>

    <div class="col-8 col-xl-6 form-group">
      <label class="textOut-w" for="password-confirm">Confirm Password</label>
      <input id="password-confirm" type="password" name="password_confirmation" required class="form-control" placeholder="Password" />
    </div>

    <div class="col-2 col-xl-3"></div>
    <div class="col-2 col-xl-3"></div>

    <div class="col-8 col-xl-6">
      <div class="btn-login-register">
        <button type="submit" class="btn btn-secondary">
          Register
        </button>
      </div>
      <small id="signIn" class="form-text text-muted signin">Already have an account?
        <a href="{{ route('login') }}" class="signin">Log In.</a>
      </small>
    </div>

    <div class="col-2 col-xl-3"></div>
  </div>
</form>

@endsection