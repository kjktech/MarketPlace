<h1 class="post__title">

  {{\ucwords(\strtolower($post->title))}}
  @if($post->subtitle)
  - {{\ucwords(\strtolower($post->subtitle))}}
  @endif
</h1>
<div class="post__details">
    <span class="">Author: {{$post->author->name}}</span>
    <span class="post__details-seperator">-</span>
    <span class="post__time-stamp">Date: {{$post->posted_at->format('F d, Y')}}</span>
</div>

<div class="post__content">
    <div class="post__paragraph">
        {!! $post->post_body_output() !!}
    </div>
    <a href="">
        <img src="{{$post->image_url()}}" width="100%" class="post__img">
    </a>
    <blockquote cite="" class="post__paragraph post__pagagraph--quote">

    </blockquote>

    <p class="post__paragraph">
      <!--
        Lorem Ipsum is simply dummy text of the printing and
        typesetting industry. Lorem Ipsum has been the
        industry’s standard dummy text ever since the 1500s,
        when an unknown printer took a galley of type and scrambled
        it to make a type specimen book. It has survived not only
        five centuries, but also the leap into electronic typesetting,
        remaining essentially unchanged. It was popularised in the
        1960s with the release of Letraset sheets containing Lorem Ipsum passages, and
        more recently with desktop publishing software like Aldus
        PageMaker including versions of Lorem Ipsum.
    -->
    </p>

</div>
