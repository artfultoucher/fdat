@extends('frontend.layouts.app')
@section('title', $code . ' - ' . $obj->title)
@section('breadcrumbs', Breadcrumbs::render('matters_single', $code))

@section('content')

<div class="card mt-5">
    <div class="card-header"><span class="h5 fancy">{{$code}} - {{$obj->title}}</span>
        @auth ()
            @if ($logged_in_user->has_subscribed($code))
                <span class="float-right badge badge-info"><i class="fas fa-check"></i>Subscribed</span>
            @endif
        @endauth
    </div>
    <div class="card-body">
        @foreach ($obj->links as $link)
        <div class="row">
            <div class="col-2">{{$link->name}}</div>
            <div class="col"><a href="{{$link->url}}">{{$link->url}}</a></div>
        </div>
        @endforeach
    </div>
    <div class="card-footer">
        You can also <a href="{{route('frontend.matters')}}">view all</a> of these subscription tags.
    </div>
</div>


@endsection
