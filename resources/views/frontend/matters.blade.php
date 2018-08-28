@extends('frontend.layouts.app')

@section('title', $code . ' - ' . $obj->title)

@section('content')

<div class="card mt-5">
    <div class="card-header h3 fancy">{{$code}} - {{$obj->title}}</div>
    <div class="card-body">
        @foreach ($obj->links as $link)
        <div class="row">
            <div class="col-2">{{$link->name}}</div>
            <div class="col"><a href="{{$link->url}}">{{$link->url}}</a></div>
        </div>
        @endforeach
    </div>
</div>
@endsection
