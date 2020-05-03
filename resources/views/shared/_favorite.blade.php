<a title="Click to mark as Favorite Question (Click anagin to undo)" class="favorite mt-2 {{ Auth::guest() ? 'off': ($model->is_favorited ? 'favorited' : '')}}"
    onClick="event.preventDefault();document.getElementById('favorite-question-{{$model->id}}').submit();"
    >
    <i class="fas fa-star fa-2x" aria-hidden="true"></i>
    <span class="favorites-count">{{$model->favorites_count}}</span>
</a>
<form id="favorite-question-{{$model->id}}" action="/questions/{{$model->id}}/favorites" method="POST" style="display: none;">
    @csrf
    @if($model->is_favorited)
        @method('DELETE')
    @endif
</form>