@extends('frontend.layouts.app')
@section('title', $page_title)
@if (isset($person))
    @section('breadcrumbs', Breadcrumbs::render($breadcrumb_name, $person))
@else
    @section('breadcrumbs', Breadcrumbs::render($breadcrumb_name))
@endif
@section('content')
<h3>{{$page_title}} <span class="badge badge-pill badge-secondary">{{count($projects)}}</span></h3>
@include('frontend.includes.only_public_warning')
@forelse ($projects as $project)
  @if ($loop->first)
      <div class="card-columns">
  @endif
  @include('frontend.includes.small_project_card')
  @if ($loop->last)
      </div>
  @endif
@empty
  <p><strong>No visible projects.</strong></p>
@endforelse
@endsection
