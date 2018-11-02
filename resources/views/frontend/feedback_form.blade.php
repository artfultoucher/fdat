@extends('frontend.layouts.app')
@section('title', 'Feedback Form')
@section('breadcrumbs', Breadcrumbs::render('feedback_form'))

@section('content')
    <div class="row mb-4">
        <div class="col">
          <h3>Assess and Comment</h3>
          <p>You are assessing a document submission from <strong>{{$doc->uploader_name()}}</strong>.</p>
          <p>
          @if ($doc->is_marker())
              You can <strong>grade</strong> and <strong>comment</strong> on this submission.
          @else
              You can <strong>only comment</strong> but not grade this submission.
          @endif
              <a href="{{route('frontend.deliverable.all_requests')}}">See here</a> for details.
          </p>
          {{ html()->modelForm($doc , 'PATCH', route('frontend.deliverable.feedback_process', $doc->id))->open() }}
          @if ($doc->is_marker())
          <div class="form-group form-inline">
          <label for="mark">Awarded marks out of 100: </label>
          <input type="number" class="form-control" value="{{$doc->mark}}" name="mark" id="mark" min="0" max="100" style="width: 5em;">
          </div><!--form-group-->
          <div class="form-group form-inline">
          <label for="graded">Make this feedback and assessment final: </label>
          <input type="checkbox" class="form-control" name="graded" id="graded" value="1">
          </div>
          @endif
          <div class="form-group form-inline">
          <label for="graded">Notify student after saving: </label>
          <input type="checkbox" class="form-control" name="notify" id="notify" value="1" checked>
          </div>
          <div class="form-group">
            {{ html()->label('Feedback to project student.')->for('comment') }}
            @include('frontend.includes.markdown_label')
            {{ html()->textarea('comment')->class('form-control')->attribute('rows', 10) }}
          </div><!--form-group-->
          <div class="form-group">
            {{ html()->label('Private comments. Only the supervisor and the second reader can see this.')->for('private_comment') }}
            @include('frontend.includes.markdown_label')
            {{ html()->textarea('private_comment')->class('form-control')->attribute('rows', 10) }}
          </div><!--form-group-->
          <div class="form-group mb-0 clearfix">
           {{ form_submit('Save') }}
          </div><!--form-group-->
          {{ html()->closeModelForm() }}
        </div><!--col-->
    </div><!--row-->
@endsection
