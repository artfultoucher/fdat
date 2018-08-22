@extends('frontend.layouts.app')
@section('title', $title)
@section('breadcrumbs', Breadcrumbs::render($breadcrumb_name))
@section('content')
<h2>{{$title}} ({{ count($persons) }})</h2>
<small>Click the avatars to view details.</small>
<ul class="list-group zebra">
@forelse ($persons as $person)
   <li class="list-group-item">
      @include('frontend.includes.small_user_listing')
   </li>
@empty
  Nothing  to show.
@endforelse
</ul>
@endsection
