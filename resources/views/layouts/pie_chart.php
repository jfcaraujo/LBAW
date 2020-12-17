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
  
  <body onload="updatePieChartValues($completed, $uncompleted)">
    <div class="container-fluid main-div">
      
        @include('partials.navbar')
        
      <div class="row">

        @include('partials.sidebar', ['project_id' => $id])

        <div class="col-xl-11 col-lg-12 site-scope">
            <div class="row">
                <!-- sem botao -> col-md-12 col-12 -->
                <div class="col-md-9 col-9">
                    <h2>@yield('title')</h2>
                </div>
                <div
              class="col-md-3 col-3 d-flex justify-content-end align-items-end pad-to-mobile"
            >
              <a class="a-white" href="{{ route('projects/edit',$id) }}" >
                <?php $member = DB::select('SELECT member.coordinator
                  FROM member
                  WHERE 
                  member.id_profile = :idU AND 
                  member.id_project = :idP ', ["idP" => $id, "idU" => Auth::id()]); ?>
                
                @can('isMember', \App\Project::find($id))
                <button type="button" class="btn btn-dark">
                  <i class="fas fa-edit"></i>
                </button>
                @endcan
              </a>
            </div>
            </div>
          @yield('content')
        </div>

      </div>
    </div>
    @include('partials.footer')
  </body>
</html>
