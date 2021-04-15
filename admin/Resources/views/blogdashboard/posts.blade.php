@extends('panel::layouts.master-blog')

@section('content')

    @include('alert::bootstrap')

    <section class="admin__section">
        <div class="admin__back">
                                 <a href="#">
                                     <svg class="icon icon-arrow-left">
                                         <use xlink:href="#icon-arrow-left"></use>
                                     </svg>
                                     <span>Back</span>
                                 </a>
        </div>

        <div class="admin__section-body">
            <div class="admin__section-top">
                                     <svg class="icon icon-blogging-big">
                                         <use xlink:href="#icon-blogging-big"></use>
                                     </svg>
                                     <span>Blog Post</span>

                                     <a href="{{ route('blogetc.admin.create_post') }}">+ Add Post</a>
                                     <a href="#" data-toggle="modal" data-target="#editModal" class="admin__edit-product">
                                         <svg class="icon icon-pencil-edit-small" style="width:10px !important; height:10px;">
                                             <use xlink:href="#icon-pencil-edit-small"></use>
                                         </svg> Edit Post</a>
                                     <a href="#" data-toggle="modal" data-target="#deleteModal" class="admin__edit-product">
                                         <svg class="icon icon-cancel" style="width:10px !important; height:10px;">
                                             <use xlink:href="#icon-cancel"></use>
                                         </svg> Delete Post</a>
                                     <hr class="admin__body-hr mt-1">
            </div>
        </div>

        <div class="admin__section-body-top">
                                 <label class="user-notification__label mr-4">
                                     <input type="checkbox" name="new follower">
                                     <span class="admin__subscription-filter-text">All</span>
                                     <span class="user-notification__checkbox"
                                         style="border: 1px solid #C4C4C4; border-radius:0;"></span>
                                 </label>

                                 <label class="user-notification__label mr-4">
                                     <input type="checkbox" name="new follower">
                                     <span class="admin__subscription-filter-text">Most Recent</span>
                                     <span class="user-notification__checkbox"
                                         style="border: 1px solid #C4C4C4; border-radius:0;"></span>
                                 </label>

                                 <label class="user-notification__label mr-4">
                                     <input type="checkbox" name="new follower">
                                     <span class="admin__subscription-filter-text">Most Rated</span>
                                     <span class="user-notification__checkbox"
                                         style="border: 1px solid #C4C4C4; border-radius:0;"></span>
                                 </label>

                                 <label class="user-notification__label mr-4">
                                     <input type="checkbox" name="new follower">
                                     <span class="admin__subscription-filter-text">Least Rated</span>
                                     <span class="user-notification__checkbox"
                                         style="border: 1px solid #C4C4C4; border-radius:0;"></span>
                                 </label>

                                 <div class="seller-order__content-actions-print ml-auto">
                                     <input type="text" name="" id="" placeholder="Search Post"
                                         class="seller-order__content-actions-print-input">
                                 </div>
                                 <div class="newsletter__input-btn seller-order__content-actions-search ml-0">
                                     <svg class="icon iicon-search-icon">
                                         <use xlink:href="#icon-search-icon"></use>
                                     </svg>
                                 </div>
      </div>
     <div class="admin__section-table">
          <div class="admin__section-table-column admin__section-blog">
                                     <p></p>
          </div>
                                 <div class="admin__section-table-column admin__section-blog">
                                     <p>S/N</p>
                                 </div>
                                 <div class="admin__section-table-column admin__section-blog">
                                     <p>Topic</p>
                                 </div>
                                 <div class="admin__section-table-column admin__section-blog">
                                     <p>Category</p>
                                 </div>
                                 <div class="admin__section-table-column admin__section-blog">
                                     <p>Author</p>
                                 </div>
                                 <div class="admin__section-table-column admin__section-blog">
                                     <p>Date Created</p>
                                 </div>
                                 <div class="admin__section-table-column admin__section-blog">
                                     <p>Images</p>
                                 </div>
                                 <div class="admin__section-table-column admin__section-blog">
                                     <p>Tags</p>
                                 </div>
                                 <div class="admin__section-table-column admin__section-blog">
                                     <p>Action</p>
                                 </div>
                             </div>

                             <div class="admin__section-table">
                                 <div class="admin__section-table-column admin__section-blog">
                                     <p>
                                         <label class="user-notification__label" style="margin-left:18px; margin-right:18px;">
                                             <input type="checkbox" name="new follower">
                                             <span class="user-notification__checkbox"
                                                 style="border: 1px solid #C4C4C4; border-radius:0;"></span>
                                         </label>
                                     </p>
                                 </div>
                                 @foreach($posts as $post)

                                 <div class="admin__section-table-column admin__section-blog">
                                     <p>{{ $loop->iteration }}</p>
                                 </div>
                                 <div class="admin__section-table-column admin__section-blog">
                                     <p>
                                      {{ \ucwords(\strtolower($post->title)) }}
                                     </p>
                                 </div>
                                 <div class="admin__section-table-column admin__section-blog">
                                     <p>
                                      @foreach($post->categories as $category)
                                       @if(!$loop->first)
                                       , {{ $category->category_name }}
                                       @else
                                         {{ $category->category_name }}
                                       @endif
                                      @endforeach
                                     </p>
                                 </div>
                                 <div class="admin__section-table-column admin__section-blog">
                                     <p>
                                      {{ $post->author->first_name() }}
                                     </p>
                                 </div>
                                 <div class="admin__section-table-column admin__section-blog">
                                     <p>
                                      {{ $post->updated_at->format('F d, Y') }}
                                     </p>
                                 </div>
                                 <div class="admin__section-table-column admin__section-blog">
                                     <p></p>
                                 </div>
                                 <div class="admin__section-table-column admin__section-blog">
                                     <p></p>
                                 </div>
                                 <div class="admin__section-table-column admin__section-blog">
                                     <p><a href="{{$post->url()}}" target="_blank">View</a> <a href="{{$post->edit_url()}}">Edit</a></p>
                                 </div>
                                 @endforeach

                             </div>

                             <div class="seller__dashboard-content-navigate">
                                 <div class="d-flex ml-auto">
                                     <span class="seller-order__content-paging">1</span>
                                     <span class="seller-order__content-paging">of</span>
                                     <span class="seller-order__content-paging">10</span>
                                     <div class="seller__dashboard-announcement-modal-previous">
                                         <svg class="icon icon-arrow-next">
                                             <use xlink:href="#icon-arrow-next"></use>
                                         </svg>
                                     </div>
                                     <div class="seller__dashboard-announcement-modal-next active-modal">
                                         <svg class="icon icon-arrow-next">
                                             <use xlink:href="#icon-arrow-next"></use>
                                         </svg>
                                     </div>
                                 </div>
                             </div>
    </section>

@stop
