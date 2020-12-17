<div class="navBar col-xl-1 col-lg-12">
  <nav id="sidebarID" class="sidebar nav colBar">
    @if (url()->current() === route('projectForum', $project_id) )
    <a class="nav-link active textBar-selected" href="{{ route('projectForum', $project_id)}}">
      <i class="fas fa-comments"></i> Forum</a
    >
    @else
    <a class="nav-link active textBar" href="{{ route('projectForum', $project_id)}}">
      <i class="fas fa-comments"></i> Forum</a
    >
    @endif
    @if (url()->current() === route('projectTaskList', $project_id))
    <a class="nav-link active textBar-selected" href="{{ route('projectTaskList', $project_id)}}">
      <i class="fas fa-tasks"></i> Tasks</a
    >
    @else
    <a class="nav-link active textBar" href="{{ route('projectTaskList', $project_id)}}">
      <i class="fas fa-tasks"></i> Tasks</a
    >
    @endif
    @if (url()->current() === route('members', $project_id))
    <a class="nav-link active textBar-selected" href="{{ route('members', $project_id)}}">
      <i class="fas fa-user-friends"></i> Members</a
    >
    @else
    <a class="nav-link active textBar" href="{{ route('members', $project_id)}}">
      <i class="fas fa-user-friends"></i> Members</a
    >
    @endif
    @if (url()->current() === route('projectAbout', $project_id))
    <a class="nav-link active textBar-selected" href="{{ route('projectAbout', $project_id)}}">
      <i class="fas fa-question"></i> About</a
    >
    @else
    <a class="nav-link active textBar" href="{{ route('projectAbout', $project_id)}}">
      <i class="fas fa-question"></i> About</a
    >
    @endif
  </nav>
</div>
