@extends('panel::layouts.master-blog')

@section('content')

    <h1 class="h3">Welcome, {{auth()->user()->name}}</h1>
    <hr class="admin__body-hr">

    <div class="row">
      <div class="admin__sublink-wrap">
          <div class="admin__sublink-individual">
              <div class="admin__sublink-individual-item-wrap">
                <svg class="icon icon-blogging-big">
                    <use xlink:href="#icon-blogging-big"></use>
                </svg>
                  <p class="admin__sublink-individual-text">Blog Posts</p>
              </div>
              <a href="{{ route('panel.blog.posts') }}" class="admin__sublink-link">
                  <span>View</span>
                  <svg class="icon icon-view-arrow">
                      <use xlink:href="#icon-view-arrow"></use>
                  </svg>
              </a>
          </div>

          <div class="admin__sublink-individual ml-auto">
              <div class="admin__sublink-individual-item-wrap">
                  <svg class="icon icon-comment-big">
                      <use xlink:href="#icon-comment-big"></use>
                  </svg>
                  <p class="admin__sublink-individual-text">Comments</p>
              </div>
              <a href="{{ route('blogetc.admin.comments.index') }}" class="admin__sublink-link">
                  <span>View</span>
                  <svg class="icon icon-view-arrow">
                      <use xlink:href="#icon-view-arrow"></use>
                  </svg>
              </a>
          </div>

          <div class="admin__sublink-individual ml-auto">
              <div class="admin__sublink-individual-item-wrap">
                  <svg class="icon icon-blog-category-big">
                      <use xlink:href="#icon-blog-category-big"></use>
                  </svg>
                  <p class="admin__sublink-individual-text">Categories</p>
              </div>
              <a href="{{ route('blogetc.admin.categories.index') }}" class="admin__sublink-link">
                  <span>View</span>
                  <svg class="icon icon-view-arrow">
                      <use xlink:href="#icon-view-arrow"></use>
                  </svg>
              </a>
          </div>
      </div>
      <div class="admin__sublink-wrap">
          <div class="admin__sublink-individual">
              <div class="admin__sublink-individual-item-wrap">
                  <svg class="icon icon-image">
                      <use xlink:href="#icon-image"></use>
                  </svg>
                  <p class="admin__sublink-individual-text">Images</p>
              </div>
              <a href="{{ route('blogetc.admin.images.all') }}" class="admin__sublink-link">
                  <span>View</span>
                  <svg class="icon icon-view-arrow">
                      <use xlink:href="#icon-view-arrow"></use>
                  </svg>
              </a>
          </div>
      </div>
        <!--
        <div class="col-md-6 mb-3">

            <div class="row">

                <div class="col-md-2">
                    <img src="../public/images/admin/categories.png" style="width: 48px" />
                </div>
                <div class="col-md-8">


                    <ul class="list-unstyled">
                        <li class="pb-1"><a href="#articles" class="text-muted font-weight-bold">Content</a></li>
                        <li class="pb-1"><a href="/panel/pages">Manage pages</a></li>
                        <li class="pb-1"><a href="/panel/menu">Manage menu</a></li>
                    </ul>

                </div>

            </div>

        </div>

        <div class="col-md-6 mb-3">

            <div class="row">

                <div class="col-md-2">
                    <img src="../public/images/admin/design.png" style="width: 48px" />
                </div>
                <div class="col-md-8">


                    <ul class="list-unstyled">
                        <li class="pb-1"><a href="#articles" class="text-muted font-weight-bold">Design</a></li>
                        @if(module_enabled('homepage'))
                        <li class="pb-1"><a href="/panel/addons/homepage">Customize homepage</a></li>
                        @endif
                        <li class="pb-1"><a href="/panel/themes">Themes &amp; CSS</a></li>
                    </ul>

                </div>

            </div>

        </div>
        -->
        <!--
        <div class="col-md-6 mb-3">

            <div class="row">

                <div class="col-md-2">
                    <img src="{{ asset('images/admin/config.png') }}" style="width: 48px" />
                </div>
                <div class="col-md-8">


                    <ul class="list-unstyled">
                        <li class="pb-1"><a href="#articles" class="text-muted font-weight-bold">Settings</a></li>
                        <li class="pb-1"><a href="/panel/settings">General settings</a></li>
                        <li class="pb-1"><a href="/panel/fields">Fields &amp; filters</a></li>
                        <li class="pb-1"><a href="/panel/pricing-models">Pricing models</a></li>
                    </ul>

                </div>

            </div>

        </div>
      -->

    </div>


@stop
