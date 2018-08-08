@extends('frontend.layouts.app')
@section('title', count($persons) . ' Users')
@section('breadcrumbs', Breadcrumbs::render('view_role', $role))

@section('content')
<h2>{{ count($persons) }} Users</h2>
<ul class="list-group">
@forelse ($persons as $person)
   <li class="list-group-item"><img width="100" src="{{$person->picture}}" class="rounded float-left mr-2"> {{$person->full_name}}</li>
@empty
  Nothing  to show.
@endforelse
</ul>
@endsection
