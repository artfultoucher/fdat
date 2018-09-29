@extends('frontend.layouts.app')

@section('title', 'Create New Project')

@section('breadcrumbs', Breadcrumbs::render('new_project'))

@section('content')
    <h3>Create New Project</h3><p>
    Newly created projects have their visibility set to <strong>private</strong>. Only the author can see them. So you can save a draft
    proposal for later finalisation.</p>
    <div class="row mb-4">
        <div class="col">
          {{ html()->form('POST', route('frontend.project.store'))->open() }}
          <div class="form-group">
            {{ html()->label('Project Title')->for('title') }}
            {{ html()->text('title')->class('form-control')->required() }}
          </div><!--form-group-->
          <div class="form-group">
            {{ html()->label('Abstract - a concise desciption for listings etc.')->for('abstract') }}
            Plain text only.
            {{ html()->textarea('abstract')->class('form-control')->attribute('rows', 5)->required() }}
          </div><!--form-group-->
          <div class="form-group">
            {{ html()->label('Project description - detailed text for single views.')->for('description') }}
            @include('frontend.includes.markdown_label')
            {{ html()->textarea('description')->class('form-control')->attribute('rows', 10)->required() }}
          </div><!--form-group-->
          <div class="form-group">
           <label for="type">Project type (degree program). Look <a href="{{route('frontend.matters')}}">here for details</a>.</label>
           <select id="type" name='type'>
             @foreach (App\Project::$possible_types as $code)
               @if($code == old('type'))
                 <option selected>{{$code}}</option>
               @else
                 <option>{{$code}}</option>
               @endif
             @endforeach
           </select>
           </div><!--form-group-->
           <div class="form-group">
           Project scope
           <label class="radio-inline"><input type="radio" name="semester_project" value="yes" checked>Semester Project</label>
           <label class="radio-inline"><input type="radio" name="semester_project" value="no" disabled>Small Project (not yet available)</label>
         </div><!--form-group-->
          <div class="form-group mb-0 clearfix">
           {{form_cancel(url()->previous(), 'Cancel', 'btn btn-secondary text-white btn-sm')}}
           {{ form_submit('Save new project') }}
          </div><!--form-group-->
          {{ html()->form()->close() }}
        </div><!--col-->
    </div><!--row-->
@endsection
