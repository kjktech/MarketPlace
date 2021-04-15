<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>
   <!-- Font Awesome -->
   <link href="https://stackpath.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-pdapHxIh7EYuwy6K7iE41uXVxGCXY0sAjBzaElYGJUrzwodck3Lx6IE2lA0rFREo" crossorigin="anonymous">
   <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
   <link rel="stylesheet" href="{{ asset('css/qustom.css') }}">
   <link rel="stylesheet" href="{{ asset('css/main.css') }}">
   <script
     src="https://code.jquery.com/jquery-1.12.4.min.js"
     integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ="
     crossorigin="anonymous"></script>
   <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js" integrity="sha384-pPttEvTHTuUJ9L2kCoMnNqCRcaMPMVMsWVO+RLaaaYDmfSP5//dP6eKRusbPcqhZ" crossorigin="anonymous"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/spin.js/2.3.2/spin.min.js" type="text/javascript"></script>
  <style>
   i{
     color: #FFFFFF;
   }
  </style>
  </head>

  <body class="nav-md">
      <div class="container body">
        <div class="main_container">
            <div class="col-md-3 left_col {% block sidebar_class %} {% endblock sidebar_class %}">
              <div class="left_col scroll-view">
                <div class="navbar nav_title" style="border: 0;">
                  <a href="<%= page_path(@conn, :index) %>" class="admin-site_title"><span class="logo"></span></a><span class="admin-logo-text">Afiaanyi</span>
                </div>

                <div class="clearfix"></div>
                <!-- sidebar menu -->
                <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
                  <div class="menu_section">

                    <ul class="nav side-menu">
                      <li>
                        <a href="#"><i class="fa fa-dashboard"></i> Dashboard</a>
                      </li>

                    </ul>
                  </div>
                </div>
                <!-- /sidebar menu -->
              </div>

            </div>
            <div class="top_nav">
              <div class="nav_menu">
                <nav>
                  <div class="nav toggle">
                    <a id="menu_toggle"><i class="fa fa-bars"></i></a>
                  </div>

                  <ul class="nav navbar-nav navbar-right">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                        </li>
                        <li class="nav-item">
                            @if (Route::has('register'))
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            @endif
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->name }} <span class="caret"></span>
                            </a>

                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                   onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                    {{ __('Logout') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                  </ul>
                </nav>
              </div>

            </div>
            <!--
            <span class="alert alert-info span-alert" role="alert"></span>
            <span class="alert alert-danger span-alert" role="alert"></span>
            -->
            @yield('content')
            <footer>
              <div class="pull-right">
              </div>
              <div class="clearfix"></div>
           </footer>
        </div>
      </div>
  </body>
</html>
