@extends('frontend.layouts.app')

@section('title', 'Edit | ' . $project->title)

@section('breadcrumbs', Breadcrumbs::render('edit_project', $project))

@section('content')
    <div class="row mb-4">
        <div class="col">

          {{ html()->modelForm($project , 'PATCH', route('frontend.project.update', $project->id))->open() }}
          <div class="form-group">
            {{ html()->label('Project Title')->for('title') }}
            {{ html()->text('title')->class('form-control')->required() }}
          </div><!--form-group-->
          <div class="form-group">
            {{ html()->label('Abstract - a concise desciption for listings etc.')->for('abstract') }}
            {{ html()->textarea('abstract')->class('form-control')->attribute('rows', 5)->required() }}
          </div><!--form-group-->
          <div class="form-group">
            {{ html()->label('Project description - detailed text for single views. You can use <a href="https://www.markdownguide.org/cheat-sheet/">Markdown</a>.')->for('description') }}
            {{ html()->textarea('description')->class('form-control')->attribute('rows', 10)->required() }}
          </div><!--form-group-->
          <div class="form-group">
           <label for="type">Project type (degree program)</label>
           <select id="type" name='type'>
             @foreach (App\Project::$possible_types as $code)
               @if($code == $project->type)
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
           {{ form_submit('Update Project') }}
          </div><!--form-group-->
          {{ html()->closeModelForm() }}
        </div><!--col-->
    </div><!--row-->
@endsection
