<div class="media post">
    <div class="media">
        <div class="d-flex flex-column counters">
            <div class="vote">
                <strong>{{ $question->votes_count }}</strong> {{Str::plural('vote',$question->votes_count)}}
            </div>
            <div class="status {{ $question->status }}">
                <strong>{{ $question->answers_count }}</strong> {{Str::plural('answer',$question->answers_count)}}
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