@if($person->hasRole('student'))
<div class="card text-white bg-secondary mb-4" style="box-shadow: 5px 10px 8px #777777;">
@elseif ($person->hasRole('lecturer'))
<div class="card text-white bg-info mb-4" style="box-shadow: 5px 10px 8px #777777;">
@else
<div class="card  bg-light mb-4" style="box-shadow: 5px 10px 8px #777777;">
@endif
<div class="card-header h5">Public info</div><!-- card-header-->
<div class="card-body">
<div class="row">
    <div class="col-3">
        <div class="card mb-4 text-dark bg-light">
            <img class="card-img-top" src="{{ $person->picture }}" alt="Profile Picture">

            <div class="card-body">
                <h5 class="card-title">
                    {{ $person->name }}<br/>
                </h5>

                <p class="card-text">
                    <small>
                        <i class="fas fa-calendar-check"></i> {{ __('strings.frontend.general.joined') }} {{ $person->created_at->timezone(get_user_timezone())->format('F jS, Y') }}<br/>
                        @if ($person->hasRole('student'))
                            <i class="fas fa-user-graduate"></i> Student
                        @endif
                        @if ($person->hasRole('lecturer'))
                            <i class="fas fa-chalkboard-teacher"></i> Lecturer
                        @endif
                    </small>
                </p>
                @if (Auth::check() && $logged_in_user->id != $person->id)
                    <p class="text-center"><a href="{{route('frontend.mail.user', $person->id)}}" class="btn btn-outline-dark"><i class="fas fa-envelope"></i> Contact</a></p>
                @endif
            </div>
    </div>
</div>
    <div class="col">
        @if (strlen($person->interests) > 9)
          <div class="card bg-light text-dark mb-3">
            <div class="card-header">{{$person->first_name}}'s introduction</div>
            <div class="card-body">@markdown($person->interests)</div>
          </div>
          <BR>
        @endif
<ul class="list-group text-dark">
    <li class="list-group-item d-flex justify-content-between align-items-center">
      Subscribed to:
      <span>
          @if ($person->subscr_mask == 0)
              <span class="badge badge-danger">Nothing!</span>
          @else
              @foreach ($person->matters() as $matter)
                  @if ($person->has_subscribed($matter))
                  <span class="badge badge-secondary">{{$matter}}</span>
                  @endif
              @endforeach
          @endif
      </span>
    </li>
    @if ($person->supervised_projects()->isNotEmpty())
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <a href="{{route('frontend.person.supervised_projects', $person->id)}}">Supervised projects:</a>
          <span class="badge badge-primary badge-pill">{{$person->supervised_projects()->count()}}</span>
        </li>
    @endif
    @if ($person->co_supervised_projects()->isNotEmpty())
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <a href="{{route('frontend.person.sr_projects', $person->id)}}">Co-supervised projects:</a>
          <span class="badge badge-primary badge-pill">{{$person->co_supervised_projects()->count()}}</span>
        </li>
    @endif
    @if ($person->yielded_projects()->isNotEmpty())
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <a href="{{route('frontend.person.yielded_projects', $person->id)}}">Yielded projects:</a>
          <span class="badge badge-primary badge-pill">{{$person->yielded_projects()->count()}}</span>
        </li>
    @endif
    @unless (empty($person->supervised_students()))
        <li class="list-group-item d-flex justify-content-between align-items-center">
          <a href="{{route('frontend.person.supervisees', $person->id)}}">Supervised students:</a>
          <span class="badge badge-primary badge-pill">{{count($person->supervised_students())}}</span>
        </li>
    @endunless
    @if ($person->sproject_id > 0)
        <li class="list-group-item d-flex justify-content-between align-items-center">
          Working on: <span>{!! $person->link_to_sproject() !!}</span>
        </li>
    @endif
</ul>
</div><!-- col -->
</div><!-- row -->
</div><!-- card-body-->
</div><!-- card -->
