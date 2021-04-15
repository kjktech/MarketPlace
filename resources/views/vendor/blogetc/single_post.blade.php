@extends("vendor.blogetc.layout_latest",['title'=>''])
@section('styles')
@import url({{ asset('themes/' .  current_theme() .'/css/blog-article_res.css') }});
@endsection
@section("content")
    {{--https://webdevetc.com/laravel/packages/blogetc-blog-system-for-your-laravel-app/help-documentation/laravel-blog-package-blogetc#guide_to_views--}}
    <style>
    .contact.row {
      width: 90% !important;
    }
    .related-post__post p {
      font-size: 14px;
      text-align: left;
      color: black;
      text-decoration: none !important;
     }
    </style>
    <section>

       </section>

       <section>
           <div class="row__large post">

               <div class="row__large--adjusted">

                   @include("blogetc::partials.full_post_details_latest")

                   <p class="post__tag">
                     <!--
                       <span>TAGS:</span>
                       <a class="post__tag--links" href="">NEWS,</a>
                       <a class="post__tag--links" href="">MARKETPLACE,</a>
                       <a class="post__tag--links" href="">DIRECTORY,</a>
                       <a class="post__tag--links" href="">BUSINESS DIRECTORY,</a>
                       <a class="post__tag--links" href="">MARKETPLACE,</a>
                       <a class="post__tag--links" href="">ONLINE MARKETPLACE</a>
                     -->
                   </p>

               </div>


                @include("blogetc::partials.show_comments_latest")

               @if(config("blogetc.comments.type_of_comments_to_show","built_in") !== 'disabled')
                @include("blogetc::partials.add_comment_form_latest")

               @else
                   {{--Comments are disabled--}}
               @endif




               <div class="row__large--adjusted related-post">
                   <h3 class="related-post__title">
                       Related News

                   </h3>
                   <div class="dividing__line"></div>
                   <div class="related-post__container">
                     @foreach(\App\Models\BlogEtcCategory::relatedgroup($post->categories, $post->id) as $post)
                       <div class="related-post__post">
                           <a href="">
                               <img src="{{ $post->image_url()}}">
                               <p>
                                   {!! $post->generate_introduction(50) !!}
                               </p>
                           </a>
                       </div>
                       @endforeach
                   </div>

               </div>


           </div>


       </section>

@endsection
