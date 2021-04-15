{{--Used on the index page (so shows a small summary--}}
{{--See the guide on webdevetc.com for how to copy these files to your /resources/views/ directory--}}
{{--https://webdevetc.com/laravel/packages/blogetc-blog-system-for-your-laravel-app/help-documentation/laravel-blog-package-blogetc#guide_to_views--}}
<a href="{{$post->url()}}" class="first-row__blog-list">
    <div>
        <h3 class="first-row__title">
            <span class="number">01</span>{{ \ucwords(\strtolower($post->title)) }}
        </h3>
        <p class="first-row__content">
            {!! $post->generate_introduction(130) !!}
        </p>
        <div class="first-row__details">
            <span class="first-row__avatar"><img height="22px" width="22px" src="{{$post->author->avatar}}"><img
                    src=""></span>
            <span class="first-row__name">{{ $post->author->first_name() }}</span>
            <span class="first-row__timestamp">{{$post->posted_at->format('F d, Y')}}</span>
        </div>
    </div>

    <div class="first-row__img">

        <img src="{{$post->image_url()}}">
    </div>
</a>
