@extends('panel::layouts.master')

@section('content')

    @if($category_count == 1)
        <div class="alert alert-info" role="alert">
            <h2 class="h4">Getting started?</h2>
            <ul>
                <li>Start off by adding some <a href="{{ route('panel.categories.create')}}" class="alert-link">categories</a></li>
                <!--<li>Customize the <a href="{{ route('panel.settings.index')}}" class="alert-link">settings</a> of your website</a></li>-->
                <li>Tell the world!</li>
            </ul>
        </div>
    @endif
    <h1 class="h3">Welcome, {{auth()->user()->name}}</h1>
    <hr class="admin__body-hr">

    <div class="row">
      <div class="admin__sublink-wrap">
          <div class="admin__sublink-individual">
              <div class="admin__sublink-individual-item-wrap">
                  <svg class="icon icon-directory-big">
                      <use xlink:href="#icon-directory-big"></use>
                  </svg>
                  <p class="admin__sublink-individual-text">Business Directory</p>
              </div>
              <a href="{{ route('panel.business.dashboard') }}" class="admin__sublink-link">
                  <span>View</span>
                  <svg class="icon icon-view-arrow">
                      <use xlink:href="#icon-view-arrow"></use>
                  </svg>
              </a>
          </div>

          <div class="admin__sublink-individual ml-auto">
              <div class="admin__sublink-individual-item-wrap">
                  <svg class="icon icon-market-place-big">
                      <use xlink:href="#icon-market-place-big"></use>
                  </svg>
                  <p class="admin__sublink-individual-text">MARKETPLACE</p>
              </div>
              <a href="{{ route('panel.store.dashboard') }}" class="admin__sublink-link">
                  <span>View</span>
                  <svg class="icon icon-view-arrow">
                      <use xlink:href="#icon-view-arrow"></use>
                  </svg>
              </a>
          </div>

          <div class="admin__sublink-individual ml-auto">
              <div class="admin__sublink-individual-item-wrap">
                  <svg class="icon icon-users-group">
                      <use xlink:href="#icon-users-group"></use>
                  </svg>
                  <p class="admin__sublink-individual-text">USERS</p>
              </div>
              <a href="{{ route('panel.users.index') }}" class="admin__sublink-link">
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
                  <svg class="icon icon-admin-blog-big">
                      <use xlink:href="#icon-admin-blog-big"></use>
                  </svg>
                  <p class="admin__sublink-individual-text">BLOG ADMIN</p>
              </div>
              <a href="{{ route('panel.blog.dashboard') }}" class="admin__sublink-link">
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
