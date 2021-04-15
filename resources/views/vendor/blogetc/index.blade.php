@extends("vendor.blogetc.layout_latest",['title'=>$title])
@section('styles')
   @import url({{ asset('themes/'. current_theme() .'/css/blog_res.css') }});
@endsection
@section("content")
<style>
.crop {
  width: 350px;
  height: 150px;
  overflow: hidden;
}
.crop{
  width: 292px;
}
.crop img {
  width: 450px;
  height: 300px;
  margin: -75px 0 0 -100px;
}
.empty-posts{
  font-size: 18px;
  margin-top: 20px;
  margin-bottom: 40px;
}
.categories__image-box {
  border-radius: 3px !important;
}
.first-row__img img{
  border-radius: 3px !important;
}
</style>
<section>

    <div class="row first-row__container">
        <div class="first-row__wrapper">
            @if(count($posts) > 0)
            <div class="first-row__suscribe-wrapper">
                <div class="first-row__suscribe">
                    <img class="first-row__img--bg" src="{{ asset('images/blog/bg.svg') }}">
                    <div class="first-row__launch-container">
                        <a href></a>
                        <h3 class="first-row__title underlined">

                            {{\ucwords(\strtolower($posts[0]->title))}}
                        </h3>
                        <p class="first-row__content">
                            {!! $posts[0]->generate_introduction(250) !!}
                        </p>
                        <div class="first-row__details">
                            <span class="first-row__avatar"><img height="22px" width="22px" src="{{ $posts[0]->author->avatar }}"></span>
                            <span class="first-row__name">{{ $posts[0]->author->first_name() }}</span>
                            <span class="first-row__timestamp">{{ $posts[0]->posted_at->format('F d, Y') }}</span>
                        </div>

                    </div>
                </div>
            </div>
            @endif
            <div class="first-row__blog-lists-wrapper">
                <div class="first-row__blog-lists">
                  @if(count($posts) > 0)
                    @if(count($posts) > 1)
                    @foreach($posts as $post)
                      @if ($loop->first) @continue @endif
                      @include("blogetc::partials.index_loop_latest")
                    @endforeach
                    @else
                    @foreach($posts as $post)

                      @include("blogetc::partials.index_loop_latest")
                    @endforeach
                    @endif
                  @else
                     <div class='empty-posts'>
                       <br>
                       No posts made in this category
                       <br><br>
                     </div>
                  @endif
                </div>
            </div>
        </div>

    </div>
</section>

<section>

    <div class="row categories__container-outer">

        <div class="row categories__container">

            <div class="categories__box">
                <a href="{{\App\Models\BlogEtcCategory::recentgroup()[0]->url()}}">
                    <h2 class="categories__title">
                        <img class="categories__img" src="{{ asset('images/blog/business.gold.svg') }}">
                        {{\App\Models\BlogEtcCategory::recentgroup()[0]->categories[0]->category_name}}
                    </h2>
                    <div class="crop categories__image-box">
                        <img src="{{\App\Models\BlogEtcCategory::recentgroup()[0]->image_url()}}">
                    </div>
                    <h3 class="first-row__title">
                        {{\ucwords(\strtolower(\App\Models\BlogEtcCategory::recentgroup()[0]->title))}}
                    </h3>
                </a>
                <p class="first-row__content">
                    {{\App\Models\BlogEtcCategory::recentgroup()[0]->generate_introduction(100)}}
                </p>
                <div class="first-row__details">
                    <span class="first-row__avatar"><img src="{{\App\Models\BlogEtcCategory::recentgroup()[0]->author->avatar }}" height="22px" width="22px"></span>
                    <span class="first-row__name">{{\App\Models\BlogEtcCategory::recentgroup()[0]->author->first_name() }}</span>
                    <span class="first-row__timestamp">{{\App\Models\BlogEtcCategory::recentgroup()[0]->posted_at->format('F d, Y') }}</span>
                </div>

                <div class="first-row__links">
                    <a href="{{\App\Models\BlogEtcCategory::recentgroup()[0]->categories[0]->url()}}">See all in {{\App\Models\BlogEtcCategory::recentgroup()[0]->categories[0]->category_name}} <img src="{{ asset('images/blog/arrow-link.svg') }}"></a>
                </div>

            </div>

            <div class="categories__box">
                <a href="">
                    <div style="height: 35px"></div>
                  <div class="img-container">
                    <img src="{{ asset('images/blog/ad.svg') }}">
                  </div>
                </a>
            </div>


            <div class="categories__box" href="">
                <a href="{{\App\Models\BlogEtcCategory::recentgroup()[0]->url()}}">
                    <h2 class="categories__title">
                        <img class="categories__img" src="{{ asset('images/blog/business.gold.svg') }}">
                        {{\App\Models\BlogEtcCategory::recentgroup()[0]->categories[0]->category_name}}
                    </h2>
                    <div class="crop categories__image-box">
                        <img src="{{\App\Models\BlogEtcCategory::recentgroup()[0]->image_url()}}">
                    </div>
                    <h3 class="first-row__title">
                        {{\ucwords(\strtolower(\App\Models\BlogEtcCategory::recentgroup()[0]->title))}}
                    </h3>
                </a>
                <p class="first-row__content">
                    {{\App\Models\BlogEtcCategory::recentgroup()[0]->generate_introduction(100)}}
                </p>
                <div class="first-row__details">
                    <span class="first-row__avatar"><img src="{{\App\Models\BlogEtcCategory::recentgroup()[0]->author->avatar }}" height="22px" width="22px"></span>
                    <span class="first-row__name">{{\App\Models\BlogEtcCategory::recentgroup()[0]->author->first_name() }}</span>
                    <span class="first-row__timestamp">{{\App\Models\BlogEtcCategory::recentgroup()[0]->posted_at->format('F d, Y') }}</span>
                </div>

                <div class="first-row__links">
                    <a href="{{\App\Models\BlogEtcCategory::recentgroup()[0]->categories[0]->url()}}">See all in {{\App\Models\BlogEtcCategory::recentgroup()[0]->categories[0]->category_name}} <img src="{{ asset('images/blog/arrow-link.svg') }}"></a>
                </div>

            </div>



            <div class="categories__box" href="">
                <a href="{{\App\Models\BlogEtcCategory::recentgroup()[0]->url()}}">
                    <h2 class="categories__title">
                        <img class="categories__img" src="{{ asset('images/blog/business.gold.svg') }}">
                        {{\App\Models\BlogEtcCategory::recentgroup()[0]->categories[0]->category_name}}
                    </h2>
                    <div class="crop categories__image-box">
                        <img src="{{\App\Models\BlogEtcCategory::recentgroup()[0]->image_url()}}">
                    </div>
                    <h3 class="first-row__title">
                        {{\ucwords(\strtolower(\App\Models\BlogEtcCategory::recentgroup()[0]->title))}}
                    </h3>
                </a>
                <p class="first-row__content">
                    {{\App\Models\BlogEtcCategory::recentgroup()[0]->generate_introduction(100)}}
                </p>
                <div class="first-row__details">
                    <span class="first-row__avatar"><img src="{{\App\Models\BlogEtcCategory::recentgroup()[0]->author->avatar }}" height="22px" width="22px"></span>
                    <span class="first-row__name">{{\App\Models\BlogEtcCategory::recentgroup()[0]->author->first_name() }}</span>
                    <span class="first-row__timestamp">{{\App\Models\BlogEtcCategory::recentgroup()[0]->posted_at->format('F d, Y') }}</span>
                </div>

                <div class="first-row__links">
                    <a href="{{\App\Models\BlogEtcCategory::recentgroup()[0]->categories[0]->url()}}">See all in {{\App\Models\BlogEtcCategory::recentgroup()[0]->categories[0]->category_name}} <img src="{{ asset('images/blog/arrow-link.svg') }}"></a>
                </div>

            </div>

            <div class="categories__box" href="">
                <a href="{{\App\Models\BlogEtcCategory::recentgroup()[0]->url()}}">
                    <h2 class="categories__title">
                        <img class="categories__img" src="{{ asset('images/blog/business.gold.svg') }}">
                        {{\App\Models\BlogEtcCategory::recentgroup()[0]->categories[0]->category_name}}
                    </h2>
                    <div class="crop categories__image-box">
                        <img src="{{\App\Models\BlogEtcCategory::recentgroup()[0]->image_url()}}">
                    </div>
                    <h3 class="first-row__title">
                        {{\ucwords(\strtolower(\App\Models\BlogEtcCategory::recentgroup()[0]->title))}}
                    </h3>
                </a>
                <p class="first-row__content">
                    {{\App\Models\BlogEtcCategory::recentgroup()[0]->generate_introduction(100)}}
                </p>
                <div class="first-row__details">
                    <span class="first-row__avatar"><img src="{{\App\Models\BlogEtcCategory::recentgroup()[0]->author->avatar }}" height="22px" width="22px"></span>
                    <span class="first-row__name">{{\App\Models\BlogEtcCategory::recentgroup()[0]->author->first_name() }}</span>
                    <span class="first-row__timestamp">{{\App\Models\BlogEtcCategory::recentgroup()[0]->posted_at->format('F d, Y') }}</span>
                </div>

                <div class="first-row__links">
                    <a href="{{\App\Models\BlogEtcCategory::recentgroup()[0]->categories[0]->url()}}">See all in {{\App\Models\BlogEtcCategory::recentgroup()[0]->categories[0]->category_name}} <img src="{{ asset('images/blog/arrow-link.svg') }}"></a>
                </div>

            </div>

            <div class="categories__box" href="">
                <a href="{{\App\Models\BlogEtcCategory::recentgroup()[0]->url()}}">
                    <h2 class="categories__title">
                        <img class="categories__img" src="{{ asset('images/blog/business.gold.svg') }}">
                        {{\App\Models\BlogEtcCategory::recentgroup()[0]->categories[0]->category_name}}
                    </h2>
                    <div class="crop categories__image-box">
                        <img src="{{\App\Models\BlogEtcCategory::recentgroup()[0]->image_url()}}">
                    </div>
                    <h3 class="first-row__title">
                        {{\ucwords(\strtolower(\App\Models\BlogEtcCategory::recentgroup()[0]->title))}}
                    </h3>
                </a>
                <p class="first-row__content">
                    {{\App\Models\BlogEtcCategory::recentgroup()[0]->generate_introduction(100)}}
                </p>
                <div class="first-row__details">
                    <span class="first-row__avatar"><img src="{{\App\Models\BlogEtcCategory::recentgroup()[0]->author->avatar }}" height="22px" width="22px"></span>
                    <span class="first-row__name">{{\App\Models\BlogEtcCategory::recentgroup()[0]->author->first_name() }}</span>
                    <span class="first-row__timestamp">{{\App\Models\BlogEtcCategory::recentgroup()[0]->posted_at->format('F d, Y') }}</span>
                </div>

                <div class="first-row__links">
                    <a href="{{\App\Models\BlogEtcCategory::recentgroup()[0]->categories[0]->url()}}">See all in {{\App\Models\BlogEtcCategory::recentgroup()[0]->categories[0]->category_name}} <img src="{{ asset('images/blog/arrow-link.svg') }}"></a>
                </div>

            </div>

        </div>

    </div>

    <div style="display: none;" class="loader">
        <img src="{{ asset('images/blog/ajax-loader.gif') }}" class="gif__loader">Loading
    </div>
</section>

@endsection
