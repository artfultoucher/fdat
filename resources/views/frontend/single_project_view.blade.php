@extends('frontend.layouts.app')
@section('title', 'View' . ' | ' . $project->title)

@section('content')
          @switch($project->visibility)
            @case(0)
               <div class="card text-white bg-danger">
                 <div class="card-header">
                     <span class="h5">
                         <i class="fas fa-lock"></i>
                          @if ($project->is_orphan())
                            <i class="fas fa-exclamation-circle"></i>
                          @endif
                         {{ $project->title }}
                     </span><br><i class="fas fa-chalkboard"></i> {{$project->type}}
                 </div><!--card-header-->
               @break
            @case(1)
               <div class="card text-white bg-info">
                 <div class="card-header">
                     <span class="h5">
                         <i class="fas fa-user-friends"></i>
                            <i class="fas fa-exclamation-circle"></i>
                         {{ $project->title }}
                     </span><br><i class="fas fa-chalkboard"></i> {{$project->type}}
                 </div><!--card-header-->
               @break
            @default
               <div class="card text-white bg-primary">
                 <div class="card-header">
                     <span class="h5">
                         <i class="fas fa-lock-open"></i>
                            <i class="fas fa-exclamation-circle"></i>
                         {{ $project->title }}
                     </span><br><i class="fas fa-chalkboard"></i> {{$project->type}}
                 </div><!--card-header-->
          @endswitch
                <div class="card-body">
                    <div class="row mb-2">
                       <div class="col">
                         <div class="card text-white bg-secondary">
                           <div class="card-header"><strong>Abstract</strong>
                           </div>
                           <div class="card-body">
                             {{$project->abstract}}
                           </div>
                        </div><!-- card -->
                       </div>

                       @if ($project->is_owner())

                       <div class="col">
                         <div class="card text-white bg-dark">
                           <div class="card-header"><strong>Actions</strong>
                           </div>
                           <div class="card-body">

                             <div class="btn-group">
                               <div class="dropdown show">
                                 <button  class="btn btn-warning dropdown-toggle mr-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                   Modify
                                 </button>
                                 <div class="dropdown-menu">
                                   <a class="dropdown-item" href="#">Edit this project</a>
                                   <form action="{{route('frontend.project.destroy', $project->id)}}" method="post">
                                     @csrf @method('delete')
                                     <button class="dropdown-item" type="submit">Delete this project</button>
                                   </form>
                                 </div>
                               </div>

                               <div class="dropdown show">
                                 <button type="button" class="btn btn-secondary dropdown-toggle mr-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                   Visibility
                                 </button>
                                 <div class="dropdown-menu">
                                   <a class="dropdown-item" href="{{route('frontend.change_visibility', ['id' => $project->id, 'vis' => 0]) }}">Only you</a>
                                   <a class="dropdown-item" href="{{route('frontend.change_visibility', ['id' => $project->id, 'vis' => 1]) }}">Permitted users</a>
                                   <a class="dropdown-item" href="{{route('frontend.change_visibility', ['id' => $project->id, 'vis' => 2]) }}">Public</a>
                                 </div>
                               </div>
                        
                          @can('supervise projects')
                               <div class="dropdown show">
                                 <button type="button" class="btn btn-secondary dropdown-toggle mr-1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                   Engage
                                 </button>
                                 <div class="dropdown-menu">
                                   <a class="dropdown-item" href="#">Supervise</a>
                                   <a class="dropdown-item" href="#">UnSupervise</a>
                                   <a class="dropdown-item" href="#">Become Second Reader</a>
                                   <a class="dropdown-item" href="#">Dismiss Second Reader</a>
                                 </div>
                               </div>

                               <div class="dropdown show">
                                 <button type="button" class="btn btn-secondary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                   Students
                                 </button>
                                 <div class="dropdown-menu">
                                   <a class="dropdown-item" href="#">ReAssign students</a>
                                   <a class="dropdown-item" href="#">Dismiss all students</a>
                                 </div>
                               </div>
                         @endcan
                             </div><!-- btn-group -->
                           </div>
                        </div><!-- card -->
                       </div>

                     @endif

                    </div><!-- row -->
                    <div class="row">
                      <div class="col">Supervisor: <strong>{{$project->supervisor_name()}}</strong></div><div class="col">Second Reader: <strong>{{$project->second_reader_name()}}</strong></div>
                      <div class="col">Author: <strong>{{$project->author_name()}}</strong></div><div class="col">Assigned to: </div>
                    </div>
                    <div class="row">
                      <div class="col">
                        <div class="card bg-light text-dark">
                          <div class="card-header"><strong>Full Project Description</strong>
                          </div>
                          <div class="card-body">
                            {{$project->description}}
                          </div>
                       </div><!-- card -->
                      </div>
                    </div>

                </div> <!-- card-body -->
                <div class="card-footer">
                    <small class="text-dark"><i class="fas fa-industry"></i> {{$project->updated_at}} <i class="fas fa-edit"></i>{{$project->updated_at}}</small>
                </div>
            </div><!-- card -->
@endsection
