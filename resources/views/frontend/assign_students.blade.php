@extends('frontend.layouts.app')

@section('title', 'Assign Students')

@section('breadcrumbs', Breadcrumbs::render('assign_students', $project))

@section('content')
<h3>Assign Students to</h3>
<h4>{{$project->title}}</h4>
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
           {{ form_submit('Make it so') }}
          </div><!--form-group-->
          {{ html()->form()->close() }}
        </div><!--col-->
    </div><!--row-->
@endsection
