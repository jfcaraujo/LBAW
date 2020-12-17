<!DOCTYPE html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <script src="{{ asset('js/tools.js') }}" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    <link
      rel="stylesheet"
      href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
      integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh"
      crossorigin="anonymous"
    />
    <script
      src="https://code.jquery.com/jquery-3.4.1.slim.min.js"
      integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n"
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"
      integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo"
      crossorigin="anonymous"
    ></script>
    <script
      src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"
      integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6"
      crossorigin="anonymous"
    ></script>
    <link href="{{ asset('css/global.css') }}" media="all" rel="stylesheet">
    <script
      src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.12.1/js/all.min.js"
      integrity="sha256-MAgcygDRahs+F/Nk5Vz387whB4kSK9NXlDN3w58LLq0="
      crossorigin="anonymous"
    ></script>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/js/bootstrap-select.min.js"></script>
    <title>@yield('page_title')</title>
  </head>
  <body>
    <div class="container-fluid">
      <div class="row">
        <div class="image-div col-12 col-md-8">
          <div class="simple-site-scope textOut-w">
            <h2>Project Management Application</h2>
            <p>
              The project management application, is a generic management tool
              that will streamline your projects!
            </p>
            <h4>Create a team</h4>
            <p>
              With the The project management application you can add your
              professionals in one place! Assemble your team, and see the tasks
              fly away.
            </p>
            <h4>Assign tasks</h4>
            <p>
              With any project is necessary to focus on your team strength.
              Assign tasks to specific user or teams, and monitor their
              progress.
            </p>
            <h4>Discuss and evolve</h4>
            <p>
              The forum of the project menagement appication is open to any
              project members, monitor discussions and see the vision of your
              project strengthen. And if any of your team members is in need in
              one task, he can comment on the task. Cooperating was never
              simpler!
            </p>
            <h4>Good for any size</h4>
            <p>
              The project management application is good for any project. With
              the ability to create teams inside the project and the ability to
              add coordinators, you can management even the biggest project.
            </p>
          </div>
        </div>
        <div class="margin-form-div col-12 col-md-4">
          @yield('content')
        </div>
      </div>
    </div>
    @include('partials.footer')
  </body>
</html>