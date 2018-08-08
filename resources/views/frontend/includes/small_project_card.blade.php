<div class="card {!! $project->colors()['bg-col'] !!} {!! $project->colors()['text-col'] !!}" style="box-shadow: 5px 10px 8px #777777;" >
    <div class="card-header">
     @if($project->is_new())
       <span class="badge badge-secondary">New</span>
     @endif
     @if($project->is_updated())
       <span class="badge badge-secondary">Updated</span>
     @endif
     @if($project->is_available())
       <span class="badge badge-secondary">Free</span>
     @endif
     @if($project->is_orphan())
       <span class="badge badge-secondary">Orphan</span>
     @endif
     {{$project->type}}
   </div>

    <div class="card-body">
      <h5 class="card-title">{{$project->title}}</h5>
      <p class="card-text small">{{$project->abstract}}</p>
    </div>
    <div class="card-footer">
    {!! $project->icons() !!}
    <a class="btn btn-outline-dark {!! $project->colors()['text-col'] !!}" href="{{ route('frontend.project.show', $project->id)}}" role="button"><i class="fab fa-readme"></i></a>
    @if ($project->is_owner())
      <a class="btn btn-outline-dark {!! $project->colors()['text-col'] !!}" href="{{ route('frontend.project.edit', $project->id)}}" role="button"><i class="fas fa-edit"></i></a>
    @endif

   <span class="float-right"><a class="btn btn-outline-dark {!! $project->colors()['text-col'] !!}" href="{{route('frontend.person.show', $project->owner())}}">{{$project->owner_name()}}</a></span>
    </div>
</div>
