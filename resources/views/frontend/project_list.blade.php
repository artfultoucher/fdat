@extends('frontend.layouts.app')
@section('title', $page_title)
@section('breadcrumbs', Breadcrumbs::render($breadcrumb_name))
@section('content')
<h2>{{$page_title}}</h2>
<div class="card-columns">
@forelse ($projects as $project)
  @include('frontend.includes.small_project_card')
@empty
  <div class="card bg-info">
      <div class="card-body text-center">
        <p class="card-text">No visible projects.</p>
      </div>
    </div>
@endforelse
</div>
@endsection
