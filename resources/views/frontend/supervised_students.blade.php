@extends('frontend.layouts.app')
@section('title', 'Supervisees of ' . $supervisor->full_name)
@section('breadcrumbs', Breadcrumbs::render('supervised_students', $supervisor))
@section('content')
<h2>Supervisees of {{$supervisor->full_name}} ({{count($supervisor->supervised_students())}})</h2>
<small>Click the avatars to view details.</small>
<ul class="list-group">
@forelse ($supervisor->supervised_students() as $person)
   <li class="list-group-item">
       @include('frontend.includes.small_user_listing')
   </li>
@empty
  Nothing  to show.
@endforelse
</ul>
@endsection
