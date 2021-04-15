<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Afiaanyi</title>
    <meta name="csrf-token" content="{{ csrf_token() }}" id="csrf-token" ic-global-include="#csrf-token">
    <script type="text/javascript" src='https://maps.google.com/maps/api/js?sensor=false&libraries=places&key=
   AIzaSyCG_2SD-pe1Cj0T5YRbl2ocQChhfmXJ24w
   '></script>
    <!-- Bootstrap core CSS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootswatch/4.1.3/cosmo/bootstrap.min.css">
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdn.materialdesignicons.com/1.8.36/css/materialdesignicons.min.css">
    <link rel="stylesheet" href='https://cdnjs.cloudflare.com/ajax/libs/json-forms/1.6.3/css/brutusin-json-forms.min.css'/>
    <link rel="stylesheet" href='https://cdnjs.cloudflare.com/ajax/libs/pretty-checkbox/3.0.0/pretty-checkbox.min.css'/>
    <link href="https://cdn.jsdelivr.net/npm/selectize@0.12.4/dist/css/selectize.default.css" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css" />
    <link href="https://cdn.jsdelivr.net/npm/fine-uploader@5.15.4/fine-uploader/fine-uploader-gallery.css" rel="stylesheet"/>


    <!-- Custom styles for this template -->
    <link href="{{ asset('css/admin.css') }}" rel="stylesheet">
    <link href="{{ asset('themes/base/css/app_slim.css') }}" rel="stylesheet">
    <!-- Bootstrap core JavaScript -->
	<script src="https://cdn.jsdelivr.net/npm/jquery@3.3.1/dist/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/gh/Logicify/jquery-locationpicker-plugin@0.1.16/dist/locationpicker.jquery.min.js" type="text/javascript"></script>

	<script src="//cdnjs.cloudflare.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alertify.js@1.0.12/dist/js/alertify.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/intercooler@1.2.1/dist/intercooler.min.js"></script>
    <script src='https://cdn.jsdelivr.net/npm/tinymce@4.7.9/tinymce.min.js' type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/gh/brutusin/json-forms@1.6.3/dist/js/brutusin-json-forms.min.js"></script>
    <script src="{{ asset('themes/base/js/custom_selectize.js') }}" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/fine-uploader@5.16.2/jquery.fine-uploader/jquery.fine-uploader.min.js" type="text/javascript"></script>

<script src="https://formbuilder.online/assets/js/form-builder.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/locales/bootstrap-datepicker.az.min.js"></script>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery-json/2.4.0/jquery.json.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/timepicker@1.11.14/jquery.timepicker.min.js"></script>
    <link rel="stylesheet" type="text/css" href="//cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.2.17/jquery.timepicker.min.css"/>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.businessHours/1.0.1/jquery.businessHours.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jscroll@2.3.9/jquery.jscroll.min.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/sticky-kit@1.1.3/dist/sticky-kit.min.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/active-scroll@1.0.2/dist/active-scroll.min.js" type="text/javascript"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery.repeater@1.2.1/jquery.repeater.min.js" type="text/javascript"></script>


    <!-- Styles -->

    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.businessHours/1.0.1/jquery.businessHours.min.css" rel="stylesheet"/>

    <!-- Menu Toggle Script -->
    <script>
    $("#menu-toggle").click(function(e) {
        e.preventDefault();
        $("#wrapper").toggleClass("toggled");
    });
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(function () {
        $('[data-toggle="popover"]').popover();
        $('[data-toggle="tooltip"]').tooltip();
    })
    </script>
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
        <a class="nav-link" href="{{ url('/panel') }}">Afiaanyi Admin Dashboard <i class="fa fa-tags" aria-hidden="true"></i></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="{{ route('panel.store.dashboard') }}">Marketplace dashboard <i class="fa fa-tags" aria-hidden="true"></i></a>
      </li>
      <li class="nav-item">
        <a class="nav-link" target="_blank" href="{{ route('home') }}">Open website <i class="fa fa-external-link" aria-hidden="true"></i></a>
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
        <li class="nav-item"><a class="nav-link text-s logout-item" href="{{ url('logout') }}"  onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a></li>
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

  <script src="https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js" ></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/corejs-typeahead/1.2.1/bloodhound.min.js" integrity="sha256-WJlyUMyJDhWTumC7/oaAtXFRBh0rZGc7qT80egxJafw=" crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/alertify.js@1.0.12/dist/js/alertify.min.js"></script>
  <script>
  $(document).ready(function(){
       $('.datepicker').datepicker();
       $('.autocomplete').selectize({
           create: false,

       });
       $("#sidebar").stick_in_parent({offset_top: 20});

        $('#sidebar').activescroll({
            active: "active",
            offset: 20,
            animate: 1000
        });
  });

  </script>
</body>

</html>
