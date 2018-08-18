@extends('frontend.layouts.app')
@section('title', 'Supervisees of ' . $supervisor->full_name)
@section('breadcrumbs', Breadcrumbs::render('supervised_students', $supervisor))
@section('content')
<h2>Supervisees of {{$supervisor->full_name}} ({{count($supervisor->supervised_students())}})</h2>
<small>Click the avatars to view details.</small>
<ul class="list-group">
@forelse ($supervisor->supervised_students() as $person)
   <li class="list-group-item">
       <a href="{{route('frontend.person.show', $person->id)}}">
       <img width="100" src="{{$person->picture}}" class="img-thumbnail float-left mr-2"></a> {{$person->full_name}}<br>
       Subscribed to:
       @foreach ($person->matters() as $matter)
           @if ($person->has_subscribed($matter))
               <span class="badge badge-secondary">{{$matter}}</span>
           @endif
       @endforeach
   <br>
       @if ($person->supervised_projects()->isNotEmpty())
            Supervised projects: {{$person->supervised_projects()->count()}}
       @endif
       @if ($person->co_supervised_projects()->isNotEmpty())
            Co-supervised projects: {{$person->co_supervised_projects()->count()}}
       @endif
        @if ($person->sproject_id > 0)
            Working on {!! $person->link_to_sproject() !!}
        @endif
   </li>
@empty
  Nothing  to show.
@endforelse
</ul>
@endsection
