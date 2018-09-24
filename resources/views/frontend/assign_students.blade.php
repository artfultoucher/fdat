@extends('frontend.layouts.app')
@push('after-styles')
    {{ style('css/bootstrap-select.min.css') }}
@endpush
@push('after-scripts')

    {{ script('js/bootstrap-select.min.js')}}
    <script>
    $('select').selectpicker();
    </script>

@endpush

@section('title', 'Assign Students')

@section('breadcrumbs', Breadcrumbs::render('assign_students', $project))

@section('content')


    <div class="card bg-light align-self-center">
        <div class="card-header h4 fancy">Assign Students</div>

        <div class="card-body">

          <form  class="form-inline" method="post" action="{{route('frontend.project.students', $project->id )}}" >
          @csrf @method('patch')
          <div class="form-group my-3">
            <select name="assigned_ids[]" multiple class="form-control" id="students" data-selected-text-format="count"
            data-style="btn-secondary" data-width="auto" title="No students" data-live-search="true" data-header="Scroll or type to search. Click to toggle entry.">
                @foreach ($students as $id => $optiontext)
                    @if ( in_array($id, $selected_ids) )
                        <option value="{{$id}}" selected>{{$optiontext}}</option>
                    @else
                        <option value="{{$id}}">{{$optiontext}}</option>
                    @endif
                @endforeach
            </select>
            <button class="btn btn-success form-control ml-3" type="submit">Make effective</button>
            </div><!--form-group-->
          @unless (Request::is('*/all_students'))
          <p>Are you looking for a student who is <strong>not in this list</strong>? Perhaps s/he has not subscribed to <strong>{{$project->type}}</strong> matters.
          Try selecting from
          <a href="{{route('frontend.project.student_form',['id' => $project->id, 'pool' => 'all_students'])}}">all students</a>.</p>
          @endunless
      </div><!--card-body-->
      <div class="card-footer">
      <small>I am now using a multiselect plugin. Please report any quirks with older browsers or mobile devices.</small>
      </div>
  </div><!--card-->
@endsection
