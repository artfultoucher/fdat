@extends('frontend.layouts.app')
@section('title', 'Deliverable Requests')
@section('breadcrumbs', Breadcrumbs::render('deliverable_requests'))

@section('content')
<div class="card bg-light">
<div class="card-header h3 fancy">Deliverable Requests</div>
<div class="card-body">
<p>Please consult first  <a href="{{route('frontend.help', 'deliverables')}}">this help text</a> if you have any questions.</p>
@forelse ($requests as $d)
<div class="h4 fancy">{{$d->name}}
@if ($d->due_date->isPast())
    <span class="badge badge-danger float-right">Closed</span>
@endif
</div>
<table class="table table-bordered">
  <thead class="bg-info text-white">
    <tr>
      <th scope="col">Project type</th>
      <th scope="col">Hand in by</th>
      <th scope="col">Feedback due</th>
      <th scope="col">Pass mark</th>
      <th scope="col">Marked by</th>
      <th scope="col">Items received (of {{$d->expected_items}})</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td class="align-middle"><a href="{{route('frontend.matters',$d->project_type)}}">{{$d->project_type}}</a></td>
      <td class="align-middle">{{$d->due_date->toFormattedDateString()}}<br>{{$d->due_date->diffForHumans()}}</td>
      <td class="align-middle">{{$d->feedback_date->toFormattedDateString()}}<br>{{$d->feedback_date->diffForHumans()}}</td>
      <td class="align-middle">{{$d->pass_mark}}</td>
      <td class="align-middle">@if ($d->marked_by_supervisor) Supervisor<br> @endif @if ($d->marked_by_secondreader) Second Reader @endif</td>
      <td class="align-middle w-25">
        <span class="progress">
            <div class="progress-bar" role="progressbar" style="width: {{$count[$d->id] * 100 / $d->expected_items}}%;">{{$count[$d->id]}}</div>
        </span>
      </td>
    </tr>
  </tbody>
</table>
<h5>Description</h5>
@markdown($d->description)<hr><p></p>
@empty
    Nothing found.
@endforelse
</div>
</div>
@endsection
