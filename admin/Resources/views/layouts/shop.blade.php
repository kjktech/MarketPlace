<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Afiaanyi</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" id="csrf-token" ic-global-include="#csrf-token">

    <!-- Bootstrap core CSS -->
	  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/4.1.3/cosmo/bootstrap.min.css">
	  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
   <!--  <link rel="stylesheet" href="https://cdn.materialdesignicons.com/1.8.36/css/materialdesignicons.min.css">-->
   <link href="{{ asset('store/vendor/fonts/circular-std/style.css') }}" rel="stylesheet">
   <link rel="stylesheet" href="{{ asset('store/libs/css/style.css') }}">
   <link rel="stylesheet" href="{{ asset('store/vendor/fonts/fontawesome/css/fontawesome-all.css') }}">
   <link rel="stylesheet" href="{{ asset('store/vendor/charts/chartist-bundle/chartist.css') }}">
   <link rel="stylesheet" href="{{ asset('store/vendor/charts/morris-bundle/morris.css') }}">
   <link rel="stylesheet" href="{{ asset('store/vendor/fonts/material-design-iconic-font/css/materialdesignicons.min.css') }}">
   <link rel="stylesheet" href="{{ asset('store/vendor/charts/c3charts/c3.css') }}">
   <link rel="stylesheet" href="{{ asset('store/vendor/fonts/flag-icon-css/flag-icon.min.css') }}">
    <!-- Custom styles for this template -->
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    <!-- Bootstrap core JavaScript -->
	<script src="https://cdn.jsdelivr.net/npm/jquery@3.3.1/dist/jquery.min.js"></script>

	<script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
  <script src="{{ asset('store/vendor/jquery/jquery-3.3.1.min.js') }}"></script>
  <!-- bootstap bundle js -->
  <script src="{{ asset('store/vendor/bootstrap/js/bootstrap.bundle.js') }}"></script>
  <!-- slimscroll js -->
  <script src="{{ asset('store/vendor/slimscroll/jquery.slimscroll.js') }}"></script>
  <!-- main js -->




</head>

<body>
  <nav class="navbar navbar-expand-lg navbar-light bg-light fixed-top" style="padding: 0" >
  <!--
  <a class="navbar-brand" href="{{ url('/panel') }}" style="height: 48px; padding:16px 25px 10px 15px; color: #fff; font-size: 16px; width: 210px; color: #fff;     font-size: 12px; color: #4b646f;">Afiaanyi Admin Dashboard</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  -->
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="{{ url('/panel') }}">
          <svg class="icon icon-dashboard">
            <use xlink:href="#icon-dashboard"></use>
          </svg>
          Afiaanyi Admin Dashboard
          <!--<i class="fa fa-tags" aria-hidden="true"></i>-->
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('panel.store.dashboard') }}">
          <svg class="icon icon-external-link">
              <use xlink:href="#icon-external-link"></use>
          </svg>
          Marketplace dashboard
          <!--<i class="fa fa-tags" aria-hidden="true"></i>--></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" target="_blank" href="{{ route('home') }}">
          <svg class="icon icon-external-link">
            <use xlink:href="#icon-external-link"></use>
          </svg>
          Open website
          <!--<i class="fa fa-external-link" aria-hidden="true"></i>--></a>
      </li>

    </ul>
    <ul class="navbar-nav  my-2 my-lg-0">
        @role('admin')
        <!--
        <li class="nav-item">
        <a class="nav-link" href="{{ route('panel.settings.index') }}">Settings</a>
        </li>
        -->
        @endrole
        <li class="nav-item"><a class="nav-link text-s logout-item admin__logout" href="{{ url('logout') }}"  onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
          <svg class="icon icon-logout">
              <use xlink:href="#icon-logout"></use>
          </svg>
          Logout</a></li>
        <form id="logout-form" action="{{ url('logout') }}" method="POST" style="display: none;">
        {{ csrf_field() }}
        </form>
    </ul>

  </div>
</nav>
    <div id="wrapper" class="toggled">

        <!-- Sidebar -->
        <div id="sidebar-wrapper">
          <div class="user-sidebar__logo">
               <img src="{{ asset('images/logo.png') }}" alt="" class="img-fluid">
           </div>
            <ul class="sidebar-menu">

        @role('admin|super-admin')
        <li class="nav-item">
          <a class="{ active(['panel']) }}" href="{{ route('panel.business.dashboard') }}">
            <svg class="icon icon-directory-big">
              <use xlink:href="#icon-directory-big"></use>
            </svg> Business Directory </a>
        </li>
        <li class="{{ active(['panel.store.dashboard']) }}">
            <a href="{{ route('panel.store.dashboard') }}">
              <svg class="icon icon-market-place icon-white">
                <use xlink:href="#icon-market-place"></use>
              </svg> Marketplace </a>
        </li>
        <li class="{{ active(['panel.listings*']) }}">
            <a href="{{ route('panel.listings.index') }}">
              <svg class="icon icon-buffer">
                 <use xlink:href="#icon-buffer"></use>
              </svg>
               Products </a>
        </li>
        <li class="{{ active(['*orders*']) }}">
            <a href="{{ route('panel.orders.index') }}">
              <svg class="icon icon-person-profile">
                <use xlink:href="#icon-person-profile"></use>
              </svg>
               Orders </a>
        </li>
        <li class="{{ active(['panel.stores*']) }}">
            <a href="{{ route('panel.stores.index') }}">
              <svg class="icon icon-store">
                <use xlink:href="#icon-store"></use>
              </svg>
               Stores </a>
        </li>
        <li class="{{ active(['*categories*']) }}">
            <a href="{{ route('panel.categories.index') }}">
              <svg class="icon icon-blog-category">
                <use xlink:href="#icon-blog-category"></use>
              </svg>
               Product Categories </a>
        </li>
        <li class="{{ active(['panel.brands*']) }}">
            <a href="{{ route('panel.brands.index') }}">
              <svg class="icon icon-blog-category">
                <use xlink:href="#icon-blog-category"></use>
              </svg>
               Product Brands </a>
        </li>
        @role('super-admin')
        <li class="{{ active(['*users']) }}">
            <a href="{{ route('panel.users.index') }}">
              <svg class="icon icon-users-group">
                <use xlink:href="#icon-users-group"></use>
              </svg> Users </a>
        </li>
        @endrole
        <li class="{{ active(['panel.blog.dashboard']) }}">
            <a href="{{ route('panel.blog.dashboard') }}">
              <svg class="icon icon-admin-blog">
                <use xlink:href="#icon-admin-blog"></use>
              </svg>
              Blog Admin </a>
        </li>
        @else
            <!--
            <li class="{{ active(['panel.users*']) }}">
                <a href="./panel/users"><i class="fa fa-users"></i> Users </a>
            </li>
            -->
            @if(module_enabled('moderatelistings'))
                <li class="{{ active(['panel.addons.moderatelistings*']) }}">
                    <a href="./panel/addons/moderatelistings"><i class="fa fa-tags"></i> Moderate listings </a>
                </li>
            @endif
            @if(module_enabled('ratings'))
                <li class="{{ active(['panel.addons.ratings*']) }}">
                    <a href="./panel/addons/ratings/comments"><i class="fa fa-star"></i> Reviews </a>
                </li>
            @endif
        @endrole

            </ul>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">
           @include('panel::svg')
            <div class="container" id="main">
				       @yield('content')
            </div>
        </div>
        <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->
    <script src="{{ asset('store/libs/js/main-js.js') }}"></script>
    <!-- chart chartist js -->
    <script src="{{ asset('store/vendor/charts/chartist-bundle/chartist.min.js') }}"></script>
    <!-- sparkline js -->
    <script src="{{ asset('store/vendor/charts/sparkline/jquery.sparkline.js') }}"></script>
    <!-- morris js -->
    <script src="{{ asset('store/vendor/charts/morris-bundle/raphael.min.js') }}"></script>
    <script src="{{ asset('store/vendor/charts/morris-bundle/morris.js') }}"></script>
    <!-- chart c3 js -->
    <script src="{{ asset('store/vendor/charts/c3charts/c3.min.js') }}"></script>
    <script src="{{ asset('store/vendor/charts/c3charts/d3-5.4.0.min.js') }}"></script>
    <script src="{{ asset('store/vendor/charts/c3charts/C3chartjs.js') }}"></script>
    <script src="{{ asset('store/libs/js/dashboard-ecommerce.js') }}"></script>
    @yield('script')
</body>

</html>
