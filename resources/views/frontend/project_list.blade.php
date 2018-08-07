@extends('frontend.layouts.app')
@section('title', $page_title)
@section('breadcrumbs', Breadcrumbs::render($breadcrumb_name))
@section('content')
<h2>{{$page_title}}</h2>
<div class="card-columns">
@forelse ($projects as $project)
  <div class="card {!! $project->colors()['bg-col'] !!} {!! $project->colors()['text-col'] !!}">
      <div class="card-header">
       @if($project->is_new())
         <span class="badge badge-secondary">New</span>
       @endif
       @if($project->is_updated())
         <span class="badge badge-secondary">Updated</span>
       @endif
       @if($project->is_available())
         <span class="badge badge-secondary">Free</span>
       @endif
       @if($project->is_orphan())
         <span class="badge badge-secondary">Orphan</span>
       @endif
       {{$project->type}} | {{$project->title}}
     </div>

      <div class="card-body">
        <p class="card-text small">{{$project->abstract}}</p>
      </div>
      <div class="card-footer">

      <a class="btn btn-outline-dark {!! $project->colors()['text-col'] !!}" href="{{ route('frontend.project.show', $project->id)}}" role="button"><i class="fab fa-readme"></i></a>
      @if ($project->is_owner())
        <a class="btn btn-outline-dark {!! $project->colors()['text-col'] !!}" href="{{ route('frontend.project.edit', $project->id)}}" role="button"><i class="fas fa-edit"></i></a>
      @endif

     {!! $project->icons() !!}
     <span class="float-right">{{$project->author_name()}}</span>
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
