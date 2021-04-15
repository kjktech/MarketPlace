@extends("vendor.blogetc.layout_latest",['title'=>$title])
@section('styles')
   @import url({{ asset('themes/base/css/blog.css') }});
@endsection
@section("content")
<style>
.crop {
  width: 350px;
  height: 150px;
  overflow: hidden;
}

.crop img {
  width: 450px;
  height: 300px;
  margin: -75px 0 0 -100px;
}
</style>
<section>

    <div style="margin-top:50px;" class="row first-row__container">
        <div class="row first-row__wrapper">

            <div class="first-row__blog-lists-wrapper">
                <div class="first-row__blog-lists">
                  @forelse($search_results as $result)

                      <?php $post = $result->indexable; ?>
                      @if($post && is_a($post,\WebDevEtc\BlogEtc\Models\BlogEtcPost::class))
                          <h2>Search result #{{$loop->count}}</h2>
                          @include("blogetc::partials.index_loop_latest")
                      @else

                          <div style="font-size: 18px; margin-bottom: 40px;" class='alert alert-danger'>Unable to show this search result - unknown type</div>
                      @endif
                  @empty
                      <div style="font-size: 18px; margin-bottom: 40px;" class='alert alert-danger'>Sorry, but there were no results!</div>
                  @endforelse

                </div>
            </div>
        </div>

    </div>
</section>

<section>



    <div style="display: none;" class="loader">
        <img src="{{ asset('images/blog/ajax-loader.gif') }}" class="gif__loader">Loading
    </div>
</section>
<section class="contact__container">
    <div class="contact row">
        <div class="one">
            <p>
                <strong>PHONE SUPPORT</strong><br>

                <img class="" src="{{ asset('images/blog/phone.svg') }}" alt=""> +234 905 300 0056-9
            </p>
        </div>
        <div class="two">
            <p>
                <strong>EMAIL SUPPORT</strong>
            </p>
            <p>
                <img class="" src="{{ asset('images/blog/mail.svg') }}" alt=""> info@afiaayan.com
            </p>
        </div>
        <div class="three">
            <p>
                <span>
                    <strong>GET LATEST DEALS & UPDATES</strong>
                </span>
                <br>
                <i>Stay in the loop on all upcoming promotions,
                    <br> discounts and latest updates.</i>
            </p>
        </div>
        <form id="subscribe-form" method="get" action="{{route('page.subscribe')}}">
        <div class="four newsletter-wrapper">
            <input type="email" id="mail" name="email" required placeholder="Enter e-mail">
            <input id="send" type="submit" value="send">
        </div>
      </form>
    </div>
</section>
@endsection
