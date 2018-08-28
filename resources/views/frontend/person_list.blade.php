@extends('frontend.layouts.app')
@section('title', $title)
@isset($breadcrumb_object)
    @section('breadcrumbs', Breadcrumbs::render($breadcrumb_name, $breadcrumb_object))
@else
    @section('breadcrumbs', Breadcrumbs::render($breadcrumb_name))
@endisset
@section('content')
<div class="card">
  <div class="card-header h2 fancy"> {{$title}} ({{ count($persons) }}) </div>
<div class="card-body">
@include('frontend.includes.only_public_warning')
<small>Click the avatar to view a personal introduction, if given. All badges are also clickable links.</small>
<table class="table table-striped">
  <thead class="bg-secondary text-white">
    <tr>
      <th scope="col">Avatar</th>
      <th scope="col">Name, ID, Contact</th>
      <th scope="col">Engagements</th>
      <th scope="col">Subscription tags</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($persons as $person)
    <tr>
      <td><a href="{{route('frontend.person.show', $person->id)}}">
          <img width="100" src="{{$person->picture}}" class="img-thumbnail">
      </a></td>
      <td>{{$person->full_name}} @can('view student details') <br> {{$person->studentid}} @endcan
          @if (Auth::check() && $logged_in_user->id != $person->id)
              <p><a href="{{route('frontend.mail.user', $person->id)}}" class="badge badge-info"><i class="fas fa-envelope"></i> Contact</a></p>
          @endif
      </td>
      <td>
          @if ($person->supervised_projects()->isNotEmpty())
              <div>
              <a class="badge badge-primary" href="{{route('frontend.person.supervised_projects', $person->id)}}">
             {{$person->supervised_projects()->count()}} Supervised projects</a>
              </div>
          @endif
          @if ($person->co_supervised_projects()->isNotEmpty())
              <div>
              <a class="badge badge-info" href="{{route('frontend.person.sr_projects', $person->id)}}">
             {{$person->co_supervised_projects()->count()}} Co-supervised projects</a>
              </div>
          @endif
          @if ($person->yielded_projects()->isNotEmpty())
              <div>
              <a class="badge badge-dark" href="{{route('frontend.person.yielded_projects', $person->id)}}">
              {{$person->yielded_projects()->count()}} Yielded projects</a>
              </div>
          @endif
          @unless (empty($person->supervised_students()))
              <div>
               <a class="badge badge-success" href="{{route('frontend.person.supervisees', $person->id)}}">
               {{count($person->supervised_students())}} Supervised students</a>
              </div>
          @endunless
          {!! $person->project_html() !!}
      </td>
      <td>
          @foreach ($person->matters() as $matter)
              @if ($person->has_subscribed($matter))
                  <a href="{{route('frontend.matters',$matter)}}" class="badge badge-secondary">{{$matter}}</a>
              @endif
          @endforeach
      </td>
    </tr>
    @endforeach

  </tbody>
</table>
</div>
</div>
@endsection
