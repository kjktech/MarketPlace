@forelse($comments as $comment)
<div class="post__authors--quote">
    <div>
        <img src="{{$comment->author()->user->avatar}}" class="post__author-img">
    </div>
   <span class="float-right" title='{{$comment->created_at}}'><small>{{$comment->created_at->diffForHumans()}}</small></span>
    <div>
        <p class="post__author">{{$comment->author()}}</p>
        <p class="post__quote">
            {!! nl2br(e($comment->comment))!!}
        </p>
    </div>
</div>
@empty
  
@endforelse
