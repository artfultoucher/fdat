@extends('frontend.layouts.app')
@section('title', 'Deliverable Requests')
@section('content')
<div class="card bg-light">
<div class="card-header h3 fancy">Deliverable Requests</div>
<div class="card-body">
@forelse ($requests as $d)
<div class="h4 fancy">{{$d->name}}
@if ($d->due_date->isPast())
    <span class="badge badge-danger float-right">Closed</span>
@endif
</div>
<table class="table table-bordered">
  <thead class="bg-info text-white">
    <tr>
      <th scope="col">For projects of type</th>
      <th scope="col">Hand in by</th>
      <th scope="col">Feedback due</th>
      <th scope="col">Pass mark</th>
      <th scope="col">Marked by</th>
      <th scope="col">Expected#</th>
      <th scope="col">Received#</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td class="align-middle"><a href="{{route('frontend.matters',$d->project_type)}}">{{$d->project_type}}</a></td>
      <td class="align-middle">{{$d->due_date->toFormattedDateString()}}<br>{{$d->due_date->diffForHumans()}}</td>
      <td class="align-middle">{{$d->feedback_date->toFormattedDateString()}}<br>{{$d->feedback_date->diffForHumans()}}</td>
      <td class="align-middle">{{$d->pass_mark}}</td>
      <td class="align-middle">@if ($d->marked_by_supervisor) Supervisor<br> @endif @if ($d->marked_by_secondreader) Second Reader @endif</td>
      <td class="align-middle">{{$d->expected_items}}</td>
      <td class="align-middle">
        <span class="progress">
            <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="{{$d->expected_items}}">0</div>
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
