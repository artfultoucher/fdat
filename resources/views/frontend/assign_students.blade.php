@extends('frontend.layouts.app')

@section('title', 'Assign Students')

@section('breadcrumbs', Breadcrumbs::render('assign_students', $project))

@section('content')
<h4 class="display-4">Assign Students to</h4>
<p class="lead">{{$project->title}}</p>
Click to select single entry. Hold <kbd>ctrl</kbd> to add or toggle entries.
<hr>
    <div class="row mb-4">
        <div class="col">
          {{ html()->form('PATCH', route('frontend.project.students', $project->id))->open() }}
          <div class="form-group">
            {{ html()->label('Attached students')->for('students') }}
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
          <div class="form-group mb-0 clearfix">
           {{ form_submit('Make this selection effective') }}
          </div><!--form-group-->
          {{ html()->form()->close() }}
        </div><!--col-->
    </div><!--row-->
@endsection
