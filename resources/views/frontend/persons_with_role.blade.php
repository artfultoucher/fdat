@extends('frontend.layouts.app')
@section('title', count($persons) . ' Users')
@section('breadcrumbs', Breadcrumbs::render('view_role', $role))

@section('content')
<h2>{{ count($persons) }} Users</h2>
<ul class="list-group">
@forelse ($persons as $person)
   <li class="list-group-item">
       <a href="{{route('frontend.person.show', $person->id)}}">
       <img width="100" src="{{$person->picture}}" class="img-thumbnail float-left mr-2"></a> {{$person->full_name}}
       @if ($person->supervised_projects()->isNotEmpty())
            Supervised projects: {{$person->supervised_projects()->count()}}
       @endif
       @if ($person->co_supervised_projects()->isNotEmpty())
            Co-supervised projects: {{$person->co_supervised_projects()->count()}}
       @endif
   </li>
@empty
  Nothing  to show.
@endforelse
</ul>
@endsection
