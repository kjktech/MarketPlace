@extends("vendor.blogetc.layout_latest",['title'=>''])
@section('styles')
   @import url({{ asset('themes/base/css/blog.css') }});
@endsection
@section("content")
    <style>
    .response-text {
        margin: auto;
        margin-top: auto;
        margin-bottom: auto;
        width: 50%;
        margin-top: 30px;
        margin-bottom: 50px;
        font-size: 20px;
       }

    .outlined {
         border: 1px solid #CAAA3B;
         padding: 8px 25px;
         border-radius: 4px;
         margin-top: 10px;
        display: inline-block;
         -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        -ms-border-radius: 4px;
        -o-border-radius: 4px;
      }
    </style>
    <div class='response-text'>
        <h3>Thanks! Your comment has been saved!</h3>

        @if(!config("blogetc.comments.auto_approve_comments",false) )
            <p>After an admin user approves the comment, it'll appear on the site!</p>
        @endif

        <a class="outlined" href='{{$blog_post->url()}}' class='btn btn-primary'>Back to blog post</a>
    </div>

@endsection
