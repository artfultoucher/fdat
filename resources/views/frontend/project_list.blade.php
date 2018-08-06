@extends('frontend.layouts.app')
@section('title', 'List Projects')
@section('breadcrumbs', Breadcrumbs::render('projects'))
@section('content')

<div class="card-columns">
@forelse ($projects as $project)
  <div class="card {!! $project->colors()['bg-col'] !!} {!! $project->colors()['text-col'] !!}">
      <div class="card-header"> {{$project->title}}</div>
      <div class="card-body">
        <p class="card-text">{{$project->abstract}}</p>
      </div>
      <div class="card-footer">

      <a class="btn btn-outline-dark {!! $project->colors()['text-col'] !!}" href="{{ route('frontend.project.show', $project->id)}}"role="button"><i class="fab fa-readme"></i></a>
     {!! $project->icons() !!}

      </div>
  </div>
@empty
  <div class="card bg-info">
      <div class="card-body text-center">
        <p class="card-text">No visible projects.</p>
      </div>
    </div>
@endforelse
</div>

@endsection
