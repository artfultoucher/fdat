@extends('frontend.layouts.app')

@section('title', 'Assign Students')

@section('breadcrumbs', Breadcrumbs::render('assign_students', $project))

@section('content')


    <div class="card bg-light align-self-center">
        <div class="card-header h4 fancy">Assign Students</div>

        <div class="card-body">

        Click to select single entry. Hold <kbd>CTRL</kbd> to add or toggle entries.

          {{ html()->form('PATCH', route('frontend.project.students', $project->id))->open() }}
          <div class="form-group form-inline mt-3">
            <select name="assigned_ids[]" multiple class="form-control" id="students">
                @foreach ($students as $id => $optiontext)
                    @if ( in_array($id, $selected_ids) )
                        <option value="{{$id}}" selected>{{$optiontext}}</option>
                    @else
                        <option value="{{$id}}">{{$optiontext}}</option>
                    @endif
                @endforeach
            </select>
            </div><!--form-group-->
          <div class="form-group">
           {{ form_submit('Make effective') }}
          </div><!--form-group-->
          {{ html()->form()->close() }}
          @unless (Request::is('*/all_students'))
          <p>Are you looking for a student who is <strong>not in this list</strong>? Perhaps s/he has not subscribed to <strong>{{$project->type}}</strong> matters.
          Try selecting from
          <a href="{{route('frontend.project.student_form',['id' => $project->id, 'pool' => 'all_students'])}}">all students</a>.</p>
          @endunless
      </div><!--card-body-->
      <div class="card-footer">

      <small>Yes I know there are fancy multiselect plugins. Those I tried had subtle quirks.</small>
      </div>
  </div><!--card-->
@endsection
