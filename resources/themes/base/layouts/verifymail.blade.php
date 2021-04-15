<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='shortcut icon' type='image/x-icon' href="{{ asset('images/favicon.ico') }}" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
    <style>
        @import url('https://fonts.googleapis.com/css?family=Lato:100,300,300i,400');
        @import url("{{ asset('themes/' . current_theme() . '/css/terms_of_use.css')}}");
    </style>
    <link rel="stylesheet" href="{{ asset('themes/' . current_theme() . '/css/overload.css') }}">
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <title></title>
</head>


<body class="email-module">

    <header>
        <nav>
            <div class="header__top-container">
                <div class="row header__nav-content">

                    <div class="header__logo-container">
                      <a href="{{ route('home') }}">
                        <img src="{{ asset('themes/' . current_theme() . '/images/market-logo.svg')}}" class="header__logo" alt="Afiaanyi logo">
                     </a>
                    </div>

                    <div class="header__nav-list-container-outer">
                      <ul class="header__nav-list-container">
                        <li class="header__nav-list">
                            <a style="font-weight: bold; color: #E6B712 !important;" href="{{ route('page.topbrands') }}" class="header__nav-link">Afiaanyi's Top Brands</a>
                        </li>
                          <li class="header__nav-list">
                              <a href="{{ route('browsebrand') }}" class="header__nav-link">Directory</a>
                          </li>
                          <li class="header__nav-list">
                              <a href="{{ route('page.comingsoon') }}" class="header__nav-link">Shop</a>
                          </li>

                          <li class="header__nav-list">
                              <a href="{{ route('page.comingsoon') }}" class="header__nav-link">Jobs</a>
                          </li>

                      </ul>
                    </div>

                    <div class="header__right-nav">

                      @guest


                      @else
                      <p class="greeting">
                          Welcome, &nbsp;<span class="user__name">{{ Auth::user()->first_name() }}!</span>

                          <a href="">
                              <img src="{{ Auth::user()->avatar }}" class="header-nav-icon header__avatar" height="35px"
                                  width="35px">
                          </a>

                          <img class="notify" src="{{ asset('themes/' . current_theme() .'/images/notify.svg') }}">
                      </p>
                      @endguest
                    </div>

                </div>




            </div>
        </nav>
       </header>
       @yield('content')


       <section class="contact__container">
           <div class="contact row">
               <div class="one">
                   <p>
                       <strong>PHONE SUPPORT</strong><br>

                       <img class="" src="{{ asset('themes/' . current_theme() . '/images/phone.svg') }}" alt=""> +234 905 300 0056-9
                   </p>
               </div>
               <div class="two">
                   <p>
                       <strong>EMAIL SUPPORT</strong>
                   </p>
                   <p>
                       <img class="" src="{{ asset('themes/' . current_theme() . '/images/mail.svg') }}" alt=""> info@afiaanyi.com
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
               <div class="four">
                   <input type="email" id="mail" placeholder="Enter e-mail"><input id="send" type="submit" value="send">
               </div>
           </div>


       </section>
       @include('layouts.partials.footer')
      </body>

      </html>
