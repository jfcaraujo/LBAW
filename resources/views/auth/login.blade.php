@extends('layouts.layoutsimple')

@section('page_title', 'Project Management')

@section('content')

<form class="formLogin" method="POST" action="{{ route('login') }}">
    {{ csrf_field() }}

    <div class="form-group">
      <label for="email">E-mail</label>
      <input class="form-control" id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
      @if ($errors->has('email'))
          <span class="error">
            {{ $errors->first('email') }}
          </span>
      @endif
    </div>


    <div class="form-group">
      <label for="password" >Password</label>
      <input class="form-control" id="password" type="password" name="password" required>
      @if ($errors->has('password'))
          <span class="error">
              {{ $errors->first('password') }}
          </span>
      @endif
    </div>

    <!-- <label>
        <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
    </label> -->

    <div class="btn-login-register">
      <button class="btn btn-secondary" type="submit">
          Login
      </button>
    </div>

    <small id="signIn" class="form-text text-muted signin">Don't have an account?
      <a href="{{ route('register') }}" class="signin"> Sign Up. </a>
    </small>

</form>

@endsection
