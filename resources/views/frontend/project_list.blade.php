@extends('frontend.layouts.app')
@section('title', $page_title)
@if (isset($person))
    @section('breadcrumbs', Breadcrumbs::render($breadcrumb_name, $person))
@else
    @section('breadcrumbs', Breadcrumbs::render($breadcrumb_name))
@endif
@section('content')
<h4>{{$page_title}} <span class="badge badge-pill badge-secondary">{{count($projects)}}</span></h4>
@include('frontend.includes.only_public_warning')
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
