<div class="row">
  <div class="navBar col-12">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
      <a class="navbar-brand" href="{{ route('home') }}">
        <img src="{{ asset('css/images/logo.png') }}" alt="Site Logo" class="logo-div" />
      </a>

      <button
        class="navbar-toggler"
        type="button"
        data-toggle="collapse"
        data-target="#navbarText"
        aria-controls="navbarText"
        aria-expanded="false"
        aria-label="Toggle navigation"
        onclick="displaySidebar()"
      >
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarText">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item active">
            <?php $id = Auth::user()->id ?>
            <a class="nav-link" href="{{ route('user', $id)}}">
              <i class="fas fa-user-circle"></i> Profile
            </a>
          </li>
        </ul>
        <!-- <a class="logout nav-link" href="#">LogOut</a> -->
        @if (Auth::check())
        <!-- <a class="button" href="{{ url('/logout') }}"> Logout </a> -->
        <a class="button a-black" href="{{ url('/logout') }}"> Logout </a>
        @endif

      </div>
    </nav>
  </div>
</div>
