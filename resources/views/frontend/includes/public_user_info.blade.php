@push('after-scripts')
<script>
$(function () {
  $('[data-toggle="popover"]').popover({html: true})
})
</script>
@endpush

@if($person->hasRole('student'))
<div class="card text-white bg-secondary mb-4" style="box-shadow: 5px 10px 8px #777777;">
@elseif ($person->hasRole('lecturer'))
<div class="card text-white bg-info mb-4" style="box-shadow: 5px 10px 8px #777777;">
@else
<div class="card  bg-light mb-4" style="box-shadow: 5px 10px 8px #777777;">
@endif
<div class="card-header">Public info</div><!-- card-header-->
<div class="card-body">
    <h3 class="card-title">{{ $person->name }}</h3>
<div class="row">
    <div class="col-3">
        <div class="card mb-4 text-dark bg-light">
            <img class="card-img-top" src="{{ $person->picture }}" alt="Profile Picture">
            <div class="card-body">
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
         <div class="card bg-light text-dark mb-3">
           <div class="card-header h5 fancy" data-toggle="popover" data-trigger="hover" data-placement="right" title="Personal Introduction"
           data-content="Logged in users can create or modify this from <strong>My Account -> Personal Introduction</strong>.">Introduction</div>
             <div class="card-body mytext">
               @if (strlen($person->interests) > 9)
                  @markdown($person->interests)
                @else
                 <i>Nothing provided.</i> <i class="fas fa-frown"></i>
                @endif
              </div>
          </div>
    </div><!-- col -->
</div><!-- row -->
<div class="row">
    <div class="col">

    <div class="card bg-light text-dark mb-3">
      <div class="card-header h5 fancy" data-toggle="popover" data-trigger="hover" data-placement="left" title="Subscribed matters or tags"
      data-content="Logged in users can modify this from <strong>My Account -> Account Details</strong>. These tags have nothing to do with permissions. They
      just act as a <strong>filter</strong>. Some but not all views are filtered against these tags.<br>Most users should select <strong>at least
      one</strong> of these tags."> Subscribed matters</div>
      <div class="card-body">
          <span>
              @if ($person->subscr_mask == 0)
                  <span class="badge badge-danger">Nothing!</span>
              @else
                  @foreach ($person->matters() as $matter)
                      @if ($person->has_subscribed($matter))
                      <a href="{{route('frontend.matters',$matter)}}" class="badge badge-secondary">{{$matter}}</a>
                      @endif
                  @endforeach
              @endif
          </span>
      </div>
    </div>

 </div><!-- col -->

 <div class="col">

    <div class="card bg-light text-dark mb-3">
      <div class="card-header h5 fancy">Engagements</div>
      <div class="card-body">
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
      </div>
    </div>

</div><!-- col -->

</div><!-- row -->
</div><!-- card-body-->
</div><!-- card -->
