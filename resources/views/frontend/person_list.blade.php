@extends('frontend.layouts.app')
@section('title', $title)
@isset($breadcrumb_object)
    @section('breadcrumbs', Breadcrumbs::render($breadcrumb_name, $breadcrumb_object))
@else
    @section('breadcrumbs', Breadcrumbs::render($breadcrumb_name))
@endisset
@section('content')
<div class="card">
  <div class="card-header h4 fancy">{{$title}} <span class="badge badge-pill badge-secondary">{{count($persons)}}</span>
      @unless(Auth::guest() || empty($persons))
      <span class="float-right"><form action="{{route('frontend.mail.many')}}" method="post">@csrf
          @foreach ($persons as $person)
              <input type="hidden" name="ids[]" value="{{$person->id}}">
          @endforeach
          <button class="btn btn-info fancy" type="submit"><i class="fas fa-envelope"></i> Contact all</button>
      </form></span>
      @endunless
</div>
<div class="card-body">
@include('frontend.includes.only_public_warning')
<small>Click the avatar to view a personal introduction, if given. All badges are also clickable links.</small>
<table class="table table-striped table-bordered">
  <thead class="bg-secondary text-white">
    <tr>
      <th scope="col">Avatar</th>
      <th class="text-nowrap" scope="col">Name, ID, Contact</th>
      <th scope="col">Engagements</th>
      <th class="text-nowrap" scope="col">Subscribed to</th>
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
          @php
            $num = $person->supervised_projects()->count();
          @endphp
          @if ($num > 0)
              <div>
              <a class="badge badge-primary" href="{{route('frontend.person.supervised_projects', $person->id)}}">
             {{$num}} supervised {{str_plural('project', $num)}}</a>
              </div>
          @endif
          @php
            $num = $person->co_supervised_projects()->count();
          @endphp
          @if ($num > 0)
              <div>
              <a class="badge badge-info" href="{{route('frontend.person.sr_projects', $person->id)}}">
              {{$num}} co-supervised {{str_plural('project', $num)}}</a>
              </div>
          @endif
          @php
            $num = $person->yielded_projects()->count();
          @endphp
          @if ($num > 0)
              <div>
              <a class="badge badge-dark" href="{{route('frontend.person.yielded_projects', $person->id)}}">
              {{$num}} yielded {{str_plural('project', $num)}}</a>
              </div>
          @endif
          @php
            $num = count($person->supervised_students());
          @endphp
          @if ($num > 0)
              <div>
               <a class="badge badge-success" href="{{route('frontend.person.supervisees', $person->id)}}">
                {{$num}} supervised {{str_plural('student', $num)}}</a>
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
