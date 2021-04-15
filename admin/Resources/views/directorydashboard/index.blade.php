@extends('panel::layouts.master')

@section('content')

    <h1 class="h3">Welcome, {{auth()->user()->name}}</h1>
    <hr class="admin__body-hr">

    <div class="row">
      <div class="admin__sublink-wrap">
          <div class="admin__sublink-individual">
              <div class="admin__sublink-individual-item-wrap">
                <svg class="icon icon-business-category">
                      <use xlink:href="#icon-business-categories-big"></use>
                 </svg>
                  <p class="admin__sublink-individual-text">Business Categories</p>
              </div>
              <a href="{{ url('/panel') }}" class="admin__sublink-link">
                  <span>View</span>
                  <svg class="icon icon-view-arrow">
                      <use xlink:href="#icon-view-arrow"></use>
                  </svg>
              </a>
          </div>

          <div class="admin__sublink-individual ml-auto">
              <div class="admin__sublink-individual-item-wrap">
                  <svg class="icon icon-market-place-big">
                      <use xlink:href="#icon-line-chart-big"></use>
                  </svg>
                  <p class="admin__sublink-individual-text">Manage Business</p>
              </div>
              <a href="{{ route('panel.directories.index') }}" class="admin__sublink-link">
                  <span>View</span>
                  <svg class="icon icon-view-arrow">
                      <use xlink:href="#icon-view-arrow"></use>
                  </svg>
              </a>
          </div>

          <div class="admin__sublink-individual ml-auto">
              <div class="admin__sublink-individual-item-wrap">
                  <svg class="icon icon-users-group">
                      <use xlink:href="#icon-subscription-big"></use>
                  </svg>
                  <p class="admin__sublink-individual-text">Subscriptions</p>
              </div>
              <a href="{{ route('panel.payment.directory') }}" class="admin__sublink-link">
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
                      <use xlink:href="#icon-top-brands-big"></use>
                  </svg>
                  <p class="admin__sublink-individual-text">Top Brands</p>
              </div>
              <a href="{{ route('panel.topbrands') }}" class="admin__sublink-link">
                  <span>View</span>
                  <svg class="icon icon-view-arrow">
                      <use xlink:href="#icon-view-arrow"></use>
                  </svg>
              </a>
          </div>

          <div class="admin__sublink-individual">
              <div class="admin__sublink-individual-item-wrap">
                  <svg class="icon icon-admin-blog-big">
                      <use xlink:href="#icon-loyalty-big"></use>
                  </svg>
                  <p class="admin__sublink-individual-text">Loyalties</p>
              </div>
              <a href="{{ route('panel.loyalties.index') }}" class="admin__sublink-link">
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
