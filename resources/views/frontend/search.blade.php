@extends('frontend.layouts.app')
@section('title', 'Search Projects')
@isset($search_all)
    @section('breadcrumbs', Breadcrumbs::render('search_all'))
@else
    @section('breadcrumbs', Breadcrumbs::render('search_relevant'))
@endisset

@section('content')
@include('frontend.includes.only_public_warning')
<h2>Search Projects</h2>
We run fuzzy fulltext search with Levenshtein distance 2. <i class="fas fa-thumbs-up"></i>
<form method="get" action="{{route('frontend.project.search')}}">
  <div class="form-group row mt-3">
    <label for="needle" class="col-sm-4 col-form-label">Search in <strong>title</strong>, <strong>abstract</strong> and <strong>description</strong> for</label>
    <div class="col-sm-8">
        <input type="text"  class="form-control" name="needle" id="needle" value="{{$needle}}">
    </div>
  </div>
  <div class="form-group row">
    <div class="col-sm-4">Tick to include projects of <strong>unsubscribed types</strong></div>
    <div class="col-sm-8">
      <div class="form-check">
          @isset($search_all)
              <input class="form-check-input" checked type="checkbox" name="search_all" value="yes">
          @else
              <input class="form-check-input" type="checkbox" name="search_all" value="yes">
          @endisset
      </div>
    </div>
  </div>
  <div class="form-group row">
      <div class="col-sm-4"></div>
    <div class="col-sm-8">
      <button type="submit" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
    </div>
  </div>
</form>

@if ($hits)
    <hr>
    <h3>{{count($hits)}} Results</h3>
    <div class="card-columns mt-3">
        @foreach($hits as $project)
            @include('frontend.includes.small_project_card')
        @endforeach
    </div>
@elseif($needle)
    <hr>
    <p class="h4 fancy">Nothing found</p>
@endif
@endsection
