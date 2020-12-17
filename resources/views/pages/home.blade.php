@extends('layouts.layout1')

@section('page_title', 'Home')

@section('title', 'Home')

@section('content')
          <div class="row searchFilter">
            <div class="col-sm-12 col-md-6" id="homeSearchbar">
            @include('partials.searchbarnofilter')
            </div>

            <div class="col-3 col-md-0"></div>
            <div
              class="col-md-3 col-12 d-flex justify-content-end align-items-end pad-to-mobile"
            ><a class="a-white btn btn-dark" href="{{ route('projects/show_create') }}" role="button">
              
                <i class="fas fa-plus-circle"></i> 
                Create Project
              </a>
            </div>
          </div>

          <div id="projects" class="row">
            @foreach($projects as $project)
              @include('partials.project', ['project' => $project])
            @endforeach
          </div>
@endsection

