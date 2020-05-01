@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex align-items-center">
                        <h2>All Questions</h2>
                        <div class="ml-auto">
                            <a href="{{route('questions.create')}}" class="btn btn-outline-secondary">
                                Ask Question
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @include('layouts._messages')
                    @foreach($questions as $question)
                    <div class="media">
                        <div class="media">
                            <div class="d-flex flex-column counters">
                                <div class="vote">
                                    <strong>{{ $question->votes }}</strong> {{Str::plural('vote',$question->votes)}}
                                </div>
                                <div class="status {{ $question->status }}">
                                    <strong>{{ $question->answers }}</strong> {{Str::plural('answer',$question->answers)}}
                                </div>
                                <div class="view">
                                    {{ $question->views ."".Str::plural('view',$question->views)}}
                                </div>
                            </div>
                        </div>
                        <div class="media-body">
                            <div class="d-flex align-items-center">
                                <h3 class="mt-0">
                                    <a href="{{ $question->url }}">{{$question->title}}</a>
                                </h3>
                                <div class="ml-auto">
                                    {{-- @if(Auth::user()->can('update-question',$question)) --}}
                                    @can('update',$question)
                                        <a href="{{route('questions.edit',$question->id)}}" class="btn btn-outline-info">
                                            Edit
                                        </a>
                                        @endcan
                                    {{-- @endif --}}
                                    {{-- @if(Auth::user()->can('delete-question',$question)) --}}
                                    @can('delete',$question)
                                        <form class="form-delete" action="{{route('questions.destroy',$question->id)}}" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button class="btn btn-outline-danger" type="submit" onclick="return confirm('Are you Sure to Delete')">
                                                Delete
                                            </button>
                                        </form>
                                        @endcan
                                    {{-- @endif --}}
                                </div>
                            </div>

                        <p class="lead">
                            Answer By
                            <a href="{{ $question->user->url }}">
                                {{ $question->user->name }}
                            </a>
                            <small class="text-muted">
                                {{$question->created_date}}
                            </small>
                        </p>
                          {{Str::limit($question->body,250)}}
                        </div>
                      </div>
                    @endforeach
                    <div class="mx-auto">
                        {{$questions->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
