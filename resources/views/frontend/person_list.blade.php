@extends('frontend.layouts.app')
@section('title', $title)
@section('breadcrumbs', Breadcrumbs::render($breadcrumb_name))
@section('content')
<h2>{{$title}} ({{ $persons->count()}})</h2>
<ul class="list-group">
@forelse ($persons as $person)
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
   </li>
@empty
  Nothing  to show.
@endforelse
</ul>
@endsection
