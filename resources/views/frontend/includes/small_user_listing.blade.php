<a href="{{route('frontend.person.show', $person->id)}}">
    <img width="100" src="{{$person->picture}}" class="img-thumbnail float-left mr-2">
</a> <span class="h5 fancy">{{$person->full_name}}</span>
@foreach ($person->matters() as $matter)
    @if ($person->has_subscribed($matter))
        <span class="badge badge-secondary float-right">{{$matter}}</span>
    @endif
@endforeach
<br>
@if ($person->supervised_projects()->isNotEmpty())
    <a href="{{route('frontend.person.supervised_projects', $person->id)}}">Supervised projects:</a>
    <span class="badge badge-primary badge-pill">{{$person->supervised_projects()->count()}}</span>
@endif
@if ($person->co_supervised_projects()->isNotEmpty())
    <a href="{{route('frontend.person.sr_projects', $person->id)}}">Co-supervised projects:</a>
    <span class="badge badge-primary badge-pill">{{$person->co_supervised_projects()->count()}}</span>
@endif
@if ($person->yielded_projects()->isNotEmpty())
    <a href="{{route('frontend.person.yielded_projects', $person->id)}}">Yielded projects:</a>
    <span class="badge badge-primary badge-pill">{{$person->yielded_projects()->count()}}</span>
@endif
@unless (empty($person->supervised_students()))
      <a href="{{route('frontend.person.supervisees', $person->id)}}">Supervised students:</a>
      <span class="badge badge-primary badge-pill">{{count($person->supervised_students())}}</span>
@endunless
<br>
@if ($person->sproject_id > 0)
    Working on {!! $person->link_to_sproject() !!}
@endif
