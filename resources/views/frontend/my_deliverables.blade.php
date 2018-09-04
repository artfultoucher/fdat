@extends('frontend.layouts.app')
@section('title', 'My deliverables')
@section('content')
    @forelse ($docs as $doc)
        For <a href="{{route('frontend.project.show',$doc->project_id)}}">this project</a>.<br>
        <form method="post" action="{{route('frontend.deliverable.download')}}">
            @csrf
            <input type="hidden" name="doc_id" value="{{$doc->id}}">
            <input type="hidden" name="path" value="{{$doc->path}}">
            {{ form_submit('Download') }}
        </form>
    @empty
        @role('student')
        You have not uploaded any documents.
        @endrole
        @role('lecturer')
        Your project students have not uploaded any documents yet.
        @endrole
    @endforelse
@endsection
