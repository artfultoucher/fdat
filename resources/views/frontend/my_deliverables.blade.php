@extends('frontend.layouts.app')
@section('title', 'My deliverables')
@section('breadcrumbs', Breadcrumbs::render('my_deliverables'))

@section('content')

<div class="card bg-light">
<div class="card-header h3 fancy">My Deliverables</div>
<div class="card-body">
@forelse ($docs as $doc)
<table class="table table-bordered table-striped">
  <tbody>
    <tr><th class="w-25" scope="row">Uploader</th><td><a href="{{route('frontend.person.show', $doc->uploader_id)}}">{{$doc->uploader_name()}}</a></td></tr>
    <tr><th scope="row">For project</th><td><a href="{{route('frontend.project.show',$doc->project_id)}}">{{$doc->project_title()}}</a></td></tr>
    <tr><th scope="row">Request</th><td><a href="{{route('frontend.deliverable.all_requests')}}">{{$doc->request_name()}}</a></td></tr>
    <tr><th scope="row">Document</th><td>
        <div class="form-inline">
        <form method="post" action="{{route('frontend.deliverable.download')}}">
            @csrf
            <input type="hidden" name="doc_id" value="{{$doc->id}}">
            <input type="hidden" name="path" value="{{$doc->path}}">
            <button class="btn btn-sm btn-primary mx-2" width="8em" type="submit">Download</button>
        </form>
        @if($doc->is_supervisor())
        <form method="post" action="{{route('frontend.deliverable.delete')}}">
            @csrf @method('delete')
            <input type="hidden" name="doc_id" value="{{$doc->id}}">
            <input type="hidden" name="path" value="{{$doc->path}}">
            <button class="btn btn-sm btn-danger mx-2" type="submit">Delete</button>
        </form>
        @endif
        </div>
    </td></tr>
    <tr><th scope="row">Last upload</th><td>{{$doc->updated_at->diffForHumans()}}</td></tr>
    <tr><th scope="row">Marks</th><td>
        @if ($doc->graded)
            {{$doc->mark}}
        @else
            @if ($doc->is_examiner())
                <a href="{{route('frontend.deliverable.feedback_form', $doc->id)}}" class="btn btn-sm btn-success mx-2">Assess or comment</a>
            @endif
            <i>Not assessed yet.</i>
        @endif
    </td></tr>
    <tr><th scope="row">Feeedback</th><td>
        @if ($doc->comment)
            @markdown($doc->comment)
        @else
            <i>No comments yet.</i>
        @endif
    </td></tr>
    @if ($doc->is_examiner())
        <tr><th scope="row">Private Feeedback</th><td>
            @if ($doc->private_comment)
                @markdown($doc->private_comment)
            @else
                <i>Only examiners can see this.</i>
            @endif
        </td></tr>
    @endif

  </tbody>
</table>

@empty
    @role('student')
    You have not uploaded any documents.
    @endrole
    @role('lecturer')
    Your project students have not uploaded any documents yet.
    @endrole
@endforelse
</div>
</div>

@endsection
