<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel='shortcut icon' type='image/x-icon' href="{{ asset('images/favicon.ico') }}" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('themes/'. current_theme() .'/css/menu.css') }}">
    <style>
        @import url('https://fonts.googleapis.com/css?family=Lato:100,300,300i,400');
        @yield('styles')
        @import url({{ asset('themes/' .  current_theme()  . '/css/footer.css')}});
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
      <title>{{ config('app.name', 'Blog') }}</title>
      <style>

      .intl-tel-input {
         width: 100% !important;
      }
      .first-row__title {
        padding-top: 20px !important;
      }
      </style>
      <style>
          .modal {
            overflow-y: auto;
            background-color: rgba(0, 0, 0, 0.2); }
            @media screen and (max-width: 575px) {
              .signup {
                padding: 20px 20px; }
              .modal-dialog {
                margin: 30px; }
              }
          .signup__background {
             position: absolute;
             left: 0;
             top: 0;
             width: 100%;
             height: 100%;
          }

          .interest__individual-cover-img {
           width: 100px;
           height: 100px !important;
           position: absolute;
           top: -6px;
           right: 0;
         }
         .signup__form-input:focus {
           border: 1px solid #E6B712;
           box-shadow: none;
         }
         input::placeholder {
          font-size: 14px;
          color: #B5B5B5;
          font-family: inherit;
         }
         input::placeholder {
          font-size: 14px;
          color: #B5B5B5;
          font-family: inherit;
         }
         .signup__heading-text {
          font-weight: 500;
          line-height: normal;
          font-size: 18px;
          color: #000000;
          margin-bottom: 2px;
          text-transform: uppercase;
        }
        .signup__logo {
         width: 60px;
         height: 54px;
         margin: 7px auto;
       }
       .signup__heading {
        text-align: center;
       }
       .signup__form-input::placeholder {
        color: #C4C4C4;
        font-weight: 300;
       }
       .signup__form-input {
        height: 50px;
        width: 100%;
        background: #FFFFFF;
        border-radius: 4px;
        border: 1px solid rgba(65, 64, 64, 0.5);
        margin-bottom: 27px;
        color: #000000;
        font-size: 14px;
        padding: 16px 20px !important;
      }
      .signup__heading-subtext {

        color: #E6B712;
        font-weight: 500;
        line-height: normal;
        font-size: 12px;
        margin-bottom: 30px;
     }
     .signup__form-button {

        background: linear-gradient(90deg, #E6B712 31.25%, #CAAA3B 88.19%);
        border-radius: 4px;
        border: none;
        width: 100%;
        padding-top: 12px;
        padding-bottom: 12px;
        font-weight: 600;
        line-height: normal;
        font-size: 20px;
        color: #FFFFFF;
        margin-bottom: 10px;
     }
     .signup__form-subscribe {

      font-size: 10px;
      color: #000000;
      margin-left: 37px;
     }
     .login__forgot-password a {

      color: #E6B712;
      font-size: 10px;
      float: right;
     }
     .img-fluid {

      max-width: 100%;
      height: auto;
    }
    .signup__form-already-in {

      font-size: 12px;
      text-align: center;

    }
    .modal-dialog {
      width: 360px !important;

    }
    .dropdown-item {
      display: block;
      width: 100%;
      padding: 0.3rem 2.0rem;
      clear: both;
      font-weight: 400;
      color: #212529;
      text-align: inherit;
      white-space: nowrap;
      background-color: transparent;
      border: 0;

   }

   .dropdown-divider {
     height: 0;
     margin: 0.5rem 0;
     overflow: hidden;
     border-top: 1px solid #e9ecef;
    }
    .intl-tel-input {
       width: 100% !important;
    }
    </style>
    <link rel="stylesheet" href="{{ asset('themes/base/css/modal/bootstrap.css') }}">
    <style>
     .modal-dialog {

        width: 39% !important;

     }
     .signup {
      padding: 20px 50px;
      position: relative;

     }
     .dropdown-menu {
       padding: 11px;
     }
     .dropdown-menu a {
       font-size: 16px !important;
       text-decoration: none !important;
       font-weight: 400 !important;
     }
     .related-post__post img{
       border-radius: 3px !important;
     }
    </style>
    <link rel="stylesheet" href="{{ asset('themes/base/css/overload.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/14.0.8/css/intlTelInput.css">
</head>

<body>
  <header>
      <nav>
        <div class="header__top-container">
            <div class="row header__nav-content">
                <div class="header__logo-container">
                  <a href="{{ route('home') }}">
                    <img src="{{ asset('themes/' .  current_theme()  . '/images/market-logo.svg') }}" class="header__logo" alt="Afiaanyi logo">
                 </a>
                </div>

                <form action="{{ route('browsebrand') }}" method="get" class="header__form-input-container">
                    <input name="q" type="text" id="searcggh" list="search" class="header__form-input header__input-nav"
                        placeholder="Search for companies and services">
                    <!--<datalist id="search">
                        <option value="Internet Explorer">
                        <option value="Firefox">
                        <option value="Chrome">
                        <option value="Opera">
                        <option value="Safari">
                    </datalist>
                    -->
                    <input type="submit" class="header__submit">
                </form>

                <form action="{{ route('browsebrand') }}" method="get" class="header__form-input-container--tab">
                      <input name="q" type="text" class="header__form-input header__form-input--tab header__input-nav header__form-input--trans" placeholder="Search for companies and services">
                      <input type="submit" value="" class="header__submit--tab ">
                </form>

                <div class="header__nav-list_outer_container">
                    <ul class="header__nav-list-container">
                      <li class="header__nav-list">
                          <a style="font-weight: bold; color: #E6B712 !important;" href="{{ route('page.topbrands') }}" class="header__nav-link">Afiaanyi's Top Brands</a>
                      </li>
                        <li class="header__nav-list">
                            <a href="{{ route('page.directory') }}" class="header__nav-link">Directory</a>
                        </li>
                        <li class="header__nav-list">
                            <a href="{{ route('browse') }}" class="header__nav-link">Shop</a>
                        </li>

                        <li class="header__nav-list">
                            <a href="{{ route('page.comingsoon') }}" class="header__nav-link">Jobs</a>
                        </li>

                        @if( !Auth::check())
                         <li class="header__nav-list">
                            <a href="#login" data-toggle="modal" class="header__nav-link">Login</a>
                         </li>
                         <li class="header__nav-list">
                            <a href="#signup-one" data-toggle="modal" class="header__nav-link">Create an account</a>
                         </li>
                        @endif

                    </ul>
                </div>

               @if( Auth::check())
                <div class="header__right-nav">
                  <p class="greeting">
                  <ul style="float: left; padding-top: 12px; font-size: 12px; font-weight: 500;" class="header__nav-list-container">
                    <li style="padding-right: 0px;" class="header__nav-list header__nav-list--divider">
                       <span class="user__generic">Welcome, &nbsp;</span><span class="user__name">{{ Auth::user()->first_name() }}!</span>
                    </li>
                   </ul>
                   <ul style="float: left;" class="header__nav-list-container">
                     <li style="padding-left: 0px;" class="header__nav-list header__nav-list--divider">
                       <span class="dropdown">
                       <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                           <img src="{{ Auth::user()->avatar }}" class="header-nav-icon header__avatar" height="35px"
                               width="35px">
                       </a>
                       <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                           <a class="dropdown-item" href="{{ route('account.') }}">User Dashboard</a>
                           @if(Auth::user()->hasRole('admin'))
                             <div class="dropdown-divider"></div>
                              <a href="./panel" class="dropdown-item">Manage Site</a>
                           @endif
                           <div class="dropdown-divider"></div>
                           <a href="{{ route('logout') }}"  onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="dropdown-item" href="#">Logout</a>
                           <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                               {{ csrf_field() }}
                           </form>
                       </div>
                      </span>
                     </li>
                   </ul>
                 </p>
                </div>
                @endif
                <div class="header__menu_icon">
                     <img src="{{ asset('themes/' . current_theme() . '/images/resp_menu.svg')}}" alt="">
                 </div>
            </div>
        </div>

      </nav>

      <div class="blog__hero-small">
          <h2 class="blog__hero-small-title">Afiaanyi's Blog</h2>
      </div>


        <div class="full--nav row">
            <ul class="header__full-nav" style="position:relative">
               @foreach(\App\Models\BlogEtcCategory::orderBy("category_name")->limit(200)->get() as $category)
                  <li class="header__full-nav-list">
                      <a href="{{$category->url()}}" class="header__full-nav-link">
                          @if($category->icon)
                          <img src="{{ asset('images/blog/') }}/{{$category->icon->icon}}.svg">
                          @else
                          <img src="{{ asset('images/blog/') }}/new.svg">
                          @endif
                          &nbsp;{{$category->category_name}}</a>

                  </li>
               @endforeach

            </ul>


        </div>


        <div class="header__lower-nav-outer-container">

            <div class="row header__lower-wrapper">
                <div class="row header__lower-nav-inner-container">

                    <div>
                        <ul class="header__lower-nav-list-container">
                            <li class="header__lower-nav-list">
                                <a class="header__lower-nav-link" href="{{ route('blogetc.index') }}">Home</a>
                                <img src="{{ asset('images/blog/product-list/front-arrow.svg') }}">
                            </li>
                            <li class="header__lower-nav-list">
                                <a href="" class="header__lower-nav-link">Blog
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div style="visibility: hidden;" class="header__lower-nav-input-container header__lower--shift">

                        <label for="">Filter:</label>
                        <div class="custom-select" style="width:200px;">
                            <select>
                                <option value="0">Year</option>
                                <option value="1">Year</option>
                                <option value="2">Month</option>
                                <option value="3">Date</option>
                                <option value="4">Topic</option>
                            </select>
                        </div>

                    </div>
                    <div style="visibility: hidden;" class="header__lower-nav-input-container">

                        <label for="">Show me posts tagged:</label>
                        <div class="custom-select" style="width:200px;">
                            <select>
                                <option value="0">Any</option>
                                <option value="0">Any</option>
                                <option value="1">All tags</option>
                                <option value="2">Content Strategy</option>
                                <option value="3">Business</option>
                                <option value="4">Seo</option>
                                <option value="4">Social media</option>
                            </select>
                        </div>

                    </div>

                    <div class="blog__search">
                        <div class=" blog__search-input">
                          <form method="get" action='{{route("blogetc.search")}}'>
                            <input name='s' type="search" placeholder="What are you looking for?">
                          </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </header>
    @yield('content')
    <section class="contact__container">
        <div class="contact row">
            <div class="one">
                <p>
                    <strong>SUPPORT</strong>
                    <br>

                    <img class="" src="{{ asset('themes/' . current_theme() . '/images/phone.svg') }}" alt=""> +234 905 300 0056-9
                </p>
            </div>
            <div class="two">
                <p>
                    <strong>EMAIL SUPPORT</strong>
                    <br>
                    <img class="" src="{{ asset('themes/' . current_theme() . '/images/mail.svg') }}" alt=""> info@afiaayan.com
                </p>
            </div>
            <div class="three">
                <p>
                    <strong>GET LATEST DEALS & UPDATES</strong>
                    <br>
                    <i>Stay in the loop on all upcoming promotions,
                        <br> discounts and latest updates.</i>
                </p>
            </div>
            <div class="four">
                <p class="resp__text">
                    <strong>GET LATEST DEALS & UPDATES</strong>
                </p>
                <form id="subscribe-form" method="get" action="{{route('page.subscribe')}}">
                <div class="four newsletter-wrapper" style="display: flex;flex-flow: inherit;">
                    <input style="float: left;" type="email" id="mail" name="email" required placeholder="Enter e-mail">
                    <input id="send" type="submit" value="send">
                </div>
              </form>

            </div>
        </div>
    </section>
    <section class="footer hide-flow">
        <footer class="row">
            <div class="footer-inner">
                <div class="space">
                    <ul>
                        <li class="colored-list">GET TO KNOW US</li>
                        <li>
                            <a href="{{ route('page.aboutus') }}">About us</a>
                        </li>
                        <li>
                            <a href="{{ route('page.comingsoon') }}">Careers</a>
                        </li>
                        <li>
                            <a href="{{ route('blogetc.index') }}">Blog</a>
                        </li>
                        <li>
                            <a href="{{ route('page.faq') }}">FAQ</a>
                        </li>
                        <li>
                            <a href="{{ route('page.contactus') }}">Contact Us</a>
                        </li>
                    </ul>
                </div>
                <div class="space">
                    <ul>
                        <li class="colored-list">SECURED BY</li>
                        <img width="80px;" src="{{ asset('themes/' . current_theme() . '/images/commodo-seal.png') }}" /> <br>
                        <img width="80px;" src="{{ asset('themes/' . current_theme() . '/images/site-lock.png') }}" /><br>
                        <img width="80px;" src="{{ asset('themes/' . current_theme() . '/images/site-guard.png') }}" />
                    </ul>
                </div>
                <div class="space">
                    <ul>
                        <li class="colored-list">EXPLORE</li>
                        <li>
                            <a href="{{ route('page.topbrands') }}">Top Brands</a>
                        </li>
                        <li>
                            <a href="{{ route('page.privacypolicy') }}">Privacy Policy</a>
                        </li>
                        <li>
                            <a href="{{ route('page.disclaimer')}}">Disclaimer</a>
                        </li>
                        <li>
                            <a href="{{ route('page.billingpolicy')}}">Billing Policy</a>
                        </li>
                        <li>
                            <a href="{{ route('page.termsuse')}}">Terms of Use</a>
                        </li>
                        <li>
                            <a href="{{ route('page.comingsoon') }}">Advertise with Us</a>
                        </li>
                    </ul>
                </div>
                <div class="space">
                    <ul>
                        <li class="colored-list">SELL ON AFIAANYI</li>
                        <li>
                        @if(!Auth::check())
                          <a href="#registerbusiness" data-toggle="modal">Register your Business</a>
                        @else
                          <a href="{{url('create')}}">Register your Business</a>
                        @endif
                        </li>
                        <li>
                            <a href="{{ route('page.comingsoon') }}">Create a Store</a>
                        </li>
                        <li>
                            <a href="{{ route('page.comingsoon') }}">How it works</a>
                        </li>
                        <li>
                            <a href="{{ route('page.trustsafety') }}">Trust and Safety</a>
                        </li>
                    </ul>
                </div>
                <div class="space">
                    <ul>
                        <li class="colored-list">DELIVERY</li>
                        <li>
                            <a href="javascript:void(0)">EMS Parcel</a>
                        </li>
                    </ul>
                </div>
                <div class="left-space">
                    <ul>
                        <li class="colored-list big-para">DOWNLOAD AND CONNECT WITH US</li>
                    </ul>
                    <div class="mobile-app">

                    </div>

                    <a href="{{ route('page.comingsoon') }}"><img style="width: 122px;" src="{{ asset('themes/' .  current_theme()  . '/images/ios.svg') }}">
                    </a>&nbsp;&nbsp;
                    <a href="{{ route('page.comingsoon') }}"><img style="width: 122px;" src="{{ asset('themes/' .  current_theme()  . '/images/android.svg') }}"></a>

                    <p class="follow__p">Follow us:
                        <span>
                            <a href="https://www.facebook.com/afiaanyi/"><img class="icon-small" src="{{ asset('themes/' .  current_theme()  . '/images/brands/facebook-white.svg') }}" width="22px"
                                    height="22px" alt="facebook"></a>
                        </span>
                        <span>
                            <a href="https://www.instagram.com/afiaanyi/"><img class="icon-small" src="{{ asset('themes/' .  current_theme()  . '/images/brands/instagram-white.svg') }}" width="22px"
                                    height="22px" alt="instagram"></a>
                        </span>
                        <span>
                            <a href="https://www.linkedin.com/company/afiaanyi/"><img class="icon-small" src="{{ asset('themes/' .  current_theme()  . '/images/brands/linkedin-white.svg') }}" width="22px"
                                    height="22px" alt="linkedin"></a>
                        </span>
                        <span>
                            <a href="https://twitter.com/afiaanyi"><img class="icon-small" src="{{ asset('themes/' .  current_theme()  . '/images/brands/twitter-circular-button-white.svg') }}"
                                    width="22px" height="22px" alt="twitter"></a>
                        </span>
                    </p>
                    <p class="powered">Powered by
                        <img class="logo-by" src="{{ asset('themes/' . current_theme() . '/images/logo-by.svg') }}">
                    </p>
                </div>
            </div>

            <div class="footer__responsive_section">

                    <div>
                        <p class="follow__p">Follow us:

                            <a href="https://www.facebook.com/afiaanyi/">
                                <img class="icon-small" src="{{ asset('themes/' .  current_theme()  . '/images/brands/facebook-white.svg') }}" width="22px" height="22px" alt="facebook">
                            </a>


                            <a href="https://www.instagram.com/afiaanyi/">
                                <img class="icon-small" src="{{ asset('themes/' .  current_theme()  . '/images/brands/instagram-white.svg') }}" width="22px" height="22px" alt="instagram">
                            </a>


                            <a href="https://www.linkedin.com/company/afiaanyi/">
                                <img class="icon-small" src="{{ asset('themes/' .  current_theme()  . '/images/brands/linkedin-white.svg') }}" width="22px" height="22px" alt="linkedin">
                            </a>


                            <a href="https://twitter.com/afiaanyi">
                                <img class="icon-small" src="{{ asset('themes/' .  current_theme()  . '/images/brands/twitter-circular-button-white.svg') }}" width="22px" height="22px" alt="twitter">
                            </a>

                        </p>
                    </div>

                    <div class="mobile_link_responsive">
                        <ul>
                            <li class="colored-list big-para .big-para--download">DOWNLOAD & CONNECT WITH US</li>
                        </ul>

                        <div>
                            <a href="{{ route('page.comingsoon') }}">
                                <img src="{{ asset('themes/' .  current_theme()  . '/images/ios__2.png') }}">
                            </a>&nbsp;&nbsp;
                            <a href="{{ route('page.comingsoon') }}">
                                <img src="{{ asset('themes/' .  current_theme()  . '/images/android.png') }}">
                            </a>
                        </div>

                    </div>

                </div>

            <img class="footer-last-img" src="{{ asset('themes/' .  current_theme()  . '/images/awka.svg') }}">
            <!--<img class="footer-last-img" src="{{ asset('themes/' .  current_theme()  . '/images/ios.svg') }}resources/img/onitsha.svg">-->
            <img class="footer-last-img" src="{{ asset('themes/' .  current_theme()  . '/images/nccima.svg') }}">
            <img class="footer-last-img" src="{{ asset('themes/' .  current_theme()  . '/images/oni.svg') }}">

            <p class="copy">Copyright ?? 2019 Afiaanyi Services Limited, All Rights Reserved.</p>

             <img class="yb" src="{{ asset('themes/' .  current_theme()  . '/images/yb.svg') }}">
             <img class="by" src="{{ asset('themes/' .  current_theme()  . '/images/black-yellow.svg') }}">

            </footer>
    </section>
    <!-- res nav -->
    <div class="nav_menu__resp">
          <nav class="resp_menu">
              <div class="resp_menu__logo">
                  <img src="{{ asset('themes/' . current_theme() . '/images/logo-white.png') }}" width="100px" alt="">
              </div>
              <img src="{{ asset('themes/' . current_theme() . '/images/close_btn.svg') }}" class="menu__close" width="18px" alt="">
              <a href="index.html" class="resp_menu__link resp_menu__link--active">
                  <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px"
                      width="16px" height="16px" viewBox="0 0 32 32" style="enable-background:new 0 0 32 32;" xml:space="preserve">
                      <g>
                          <g>
                              <g>
                                  <path d="M29,0H3C1.346,0,0,1.346,0,3v26c0,1.654,1.346,3,3,3h26c1.654,0,3-1.346,3-3V3C32,1.346,30.654,0,29,0z M30,29    c0,0.551-0.449,1-1,1H3c-0.551,0-1-0.449-1-1V3c0-0.552,0.449-1,1-1h26c0.551,0,1,0.448,1,1V29z"
                                      data-original="#000000" class="active-path" data-old_color="#FCF7F7" fill="#FFFFFF" />
                                  <path d="M9.401,7H6.676C6.325,7,6.038,7.287,6.038,7.639v2.723c0,0.354,0.287,0.641,0.638,0.641h2.725    c0.351,0,0.638-0.287,0.638-0.641V7.639C10.039,7.287,9.752,7,9.401,7z"
                                      data-original="#000000" class="active-path" data-old_color="#FCF7F7" fill="#FFFFFF" />
                                  <rect x="11.539" y="8.062" width="14.422" height="1.875" data-original="#000000" class="active-path" data-old_color="#FCF7F7"
                                      fill="#FFFFFF" />
                                  <path d="M9.401,14H6.676c-0.351,0-0.638,0.287-0.638,0.639v2.726c0,0.351,0.287,0.638,0.638,0.638h2.725    c0.351,0,0.638-0.287,0.638-0.638v-2.726C10.039,14.287,9.752,14,9.401,14z"
                                      data-original="#000000" class="active-path" data-old_color="#FCF7F7" fill="#FFFFFF" />
                                  <rect x="11.539" y="15.062" width="14.422" height="1.875" data-original="#000000" class="active-path" data-old_color="#FCF7F7"
                                      fill="#FFFFFF" />
                                  <path d="M9.401,21H6.676c-0.351,0-0.638,0.287-0.638,0.639v2.727c0,0.351,0.287,0.638,0.638,0.638h2.725    c0.351,0,0.638-0.287,0.638-0.638v-2.727C10.039,21.287,9.752,21,9.401,21z M9.539,24.361c0,0.074-0.063,0.139-0.138,0.139H6.676    c-0.075,0-0.138-0.062-0.138-0.139v-2.727c0-0.072,0.063-0.138,0.138-0.138h2.725c0.075,0,0.138,0.062,0.138,0.138V24.361z"
                                      data-original="#000000" class="active-path" data-old_color="#FCF7F7" fill="#FFFFFF" />
                                  <rect x="11.539" y="22.062" width="14.422" height="1.875" data-original="#000000" class="active-path" data-old_color="#FCF7F7"
                                      fill="#FFFFFF" />
                              </g>
                          </g>
                      </g>
                  </svg>
                  Directory</a>
              <a href="{{ route('page.topbrands') }}" class="resp_menu__link">
                  <svg width="16px" height="16px" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M13.5973 5.10784L13.2411 5.0319C13.1959 4.97181 13.1475 4.91423 13.0924 4.86155C13.0653 4.83569 13.0359 4.81228 13.0066 4.78869C12.7141 4.55357 12.3137 4.43319 11.8118 4.43319H11.339H10.4327L9.63284 4.2627L7.42042 0.602067C7.30434 0.409966 7.15215 0.313965 7.00001 0.313965C6.84787 0.313965 6.69566 0.410015 6.57958 0.602067L4.36718 4.26265L3.56729 4.43314H2.66107H1.7549H0.829617C0.657077 4.43314 0.513186 4.54945 0.46789 4.70749C0.458456 4.74058 0.451713 4.77481 0.451713 4.81102V4.90413V5.0973L0.402663 5.10772C-0.0363437 5.20127 -0.13106 5.5305 0.191273 5.843L1.73006 7.33508V7.59831V7.86149V8.86742C1.73006 9.07604 1.89929 9.24527 2.10793 9.24527H2.70755H2.89832H3.08906L2.54341 13.1623C2.49767 13.4907 2.63667 13.6859 2.87488 13.6859C2.95915 13.6859 3.05589 13.6615 3.16123 13.6099L6.99996 11.7303L10.8386 13.6099C10.944 13.6615 11.0407 13.686 11.125 13.686C11.3633 13.686 11.5022 13.4908 11.4565 13.1624L10.9108 9.24537H10.9871C11.0263 9.24537 11.0634 9.23774 11.099 9.2266C11.168 9.20514 11.2279 9.16495 11.2737 9.11126C11.3298 9.04532 11.3651 8.9609 11.3651 8.86747V8.73886V8.47578V8.21257L11.8584 7.7342C11.9658 7.72822 12.0675 7.71639 12.1652 7.69994C12.2978 7.67767 12.4212 7.64558 12.5354 7.60404C12.7394 7.5299 12.9154 7.42686 13.0593 7.29218C13.2263 7.13574 13.3501 6.94915 13.4318 6.73492C13.4735 6.62519 13.5036 6.50782 13.5227 6.38357C13.5368 6.29199 13.5448 6.19653 13.5466 6.09729L13.8088 5.84305C14.131 5.5306 14.0364 5.2014 13.5973 5.10784ZM13.1027 6.52775C13.0425 6.71995 12.9429 6.8835 12.8011 7.01635C12.6771 7.13251 12.5199 7.2193 12.3302 7.27684C12.1432 7.33372 11.9244 7.36217 11.6736 7.36217H10.9873V8.5789V8.86747H10.8584H9.7283V4.81107H11.8118C12.2656 4.81107 12.6055 4.919 12.8314 5.13484C13.0574 5.3507 13.1704 5.65782 13.1704 6.05627C13.1704 6.22982 13.1469 6.38636 13.1027 6.52775ZM2.10798 8.22801V7.96483V7.70157V5.81272H0.829617V5.01683V4.82367V4.81112H0.888472H1.79462H4.63975V5.8128H3.36138V8.86759H3.14167H2.95093H2.76016H2.10798V8.22801ZM7.00001 8.93667C6.54256 8.93667 6.16391 8.86379 5.86418 8.71809C5.56444 8.57238 5.32139 8.3418 5.13509 8.02635C4.94882 7.71089 4.85563 7.31614 4.85563 6.84206C4.85563 6.17981 5.0401 5.66421 5.40899 5.29532C5.77796 4.92636 6.29162 4.74197 6.9502 4.74197C7.62531 4.74197 8.1455 4.9232 8.51076 5.28567C8.87602 5.64813 9.05864 6.15593 9.05864 6.80889C9.05864 7.28297 8.97884 7.67176 8.81929 7.97518C8.65974 8.27859 8.42916 8.51473 8.12755 8.68357C7.82589 8.8524 7.45013 8.93667 7.00001 8.93667Z"
                          fill="white" />
                  </svg>
                  Top Brands</a>
              <a href="{{ route('page.comingsoon') }}" class="resp_menu__link">
                  <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px"
                      viewBox="0 0 201.387 201.387" style="enable-background:new 0 0 201.387 201.387;" xml:space="preserve" width="16px"
                      height="16px">
                      <g>
                          <g>
                              <g>
                                  <path d="M129.413,24.885C127.389,10.699,115.041,0,100.692,0C91.464,0,82.7,4.453,77.251,11.916    c-1.113,1.522-0.78,3.657,0.742,4.77c1.517,1.109,3.657,0.78,4.768-0.744c4.171-5.707,10.873-9.115,17.93-9.115    c10.974,0,20.415,8.178,21.963,19.021c0.244,1.703,1.705,2.932,3.376,2.932c0.159,0,0.323-0.012,0.486-0.034    C128.382,28.479,129.679,26.75,129.413,24.885z"
                                      data-original="#000000" class="active-path" data-old_color="#ffffff" fill="#ffffff" />
                              </g>
                          </g>
                          <g>
                              <g>
                                  <path d="M178.712,63.096l-10.24-17.067c-0.616-1.029-1.727-1.657-2.927-1.657h-9.813c-1.884,0-3.413,1.529-3.413,3.413    s1.529,3.413,3.413,3.413h7.881l6.144,10.24H31.626l6.144-10.24h3.615c1.884,0,3.413-1.529,3.413-3.413s-1.529-3.413-3.413-3.413    h-5.547c-1.2,0-2.311,0.628-2.927,1.657l-10.24,17.067c-0.633,1.056-0.648,2.369-0.043,3.439s1.739,1.732,2.97,1.732h150.187    c1.231,0,2.364-0.662,2.97-1.732S179.345,64.15,178.712,63.096z"
                                      data-original="#000000" class="active-path" data-old_color="#ffffff" fill="#ffffff" />
                              </g>
                          </g>
                          <g>
                              <g>
                                  <path d="M161.698,31.623c-0.478-0.771-1.241-1.318-2.123-1.524l-46.531-10.883c-0.881-0.207-1.809-0.053-2.579,0.423    c-0.768,0.478-1.316,1.241-1.522,2.123l-3.509,15c-0.43,1.835,0.71,3.671,2.546,4.099c1.835,0.43,3.673-0.71,4.101-2.546    l2.732-11.675l39.883,9.329l-6.267,26.795c-0.43,1.835,0.71,3.671,2.546,4.099c0.263,0.061,0.524,0.09,0.782,0.09    c1.55,0,2.953-1.062,3.318-2.635l7.045-30.118C162.328,33.319,162.176,32.391,161.698,31.623z"
                                      data-original="#000000" class="active-path" data-old_color="#ffffff" fill="#ffffff" />
                              </g>
                          </g>
                          <g>
                              <g>
                                  <path d="M102.497,39.692l-3.11-26.305c-0.106-0.899-0.565-1.72-1.277-2.28c-0.712-0.56-1.611-0.816-2.514-0.71l-57.09,6.748    c-1.871,0.222-3.209,1.918-2.988,3.791l5.185,43.873c0.206,1.737,1.679,3.014,3.386,3.014c0.133,0,0.27-0.009,0.406-0.024    c1.87-0.222,3.208-1.918,2.988-3.791l-4.785-40.486l50.311-5.946l2.708,22.915c0.222,1.872,1.91,3.202,3.791,2.99    C101.379,43.261,102.717,41.564,102.497,39.692z"
                                      data-original="#000000" class="active-path" data-old_color="#ffffff" fill="#ffffff" />
                              </g>
                          </g>
                          <g>
                              <g>
                                  <path d="M129.492,63.556l-6.775-28.174c-0.212-0.879-0.765-1.64-1.536-2.113c-0.771-0.469-1.696-0.616-2.581-0.406L63.613,46.087    c-1.833,0.44-2.961,2.284-2.521,4.117l3.386,14.082c0.44,1.835,2.284,2.964,4.116,2.521c1.833-0.44,2.961-2.284,2.521-4.117    l-2.589-10.764l48.35-11.626l5.977,24.854c0.375,1.565,1.775,2.615,3.316,2.615c0.265,0,0.533-0.031,0.802-0.096    C128.804,67.232,129.932,65.389,129.492,63.556z"
                                      data-original="#000000" class="active-path" data-old_color="#ffffff" fill="#ffffff" />
                              </g>
                          </g>
                          <g>
                              <g>
                                  <path d="M179.197,64.679c-0.094-1.814-1.592-3.238-3.41-3.238H25.6c-1.818,0-3.316,1.423-3.41,3.238l-6.827,133.12    c-0.048,0.934,0.29,1.848,0.934,2.526c0.645,0.677,1.539,1.062,2.475,1.062h163.84c0.935,0,1.83-0.384,2.478-1.062    c0.643-0.678,0.981-1.591,0.934-2.526L179.197,64.679z M22.364,194.56l6.477-126.293h143.701l6.477,126.293H22.364z"
                                      data-original="#000000" class="active-path" data-old_color="#ffffff" fill="#ffffff" />
                              </g>
                          </g>
                          <g>
                              <g>
                                  <path d="M126.292,75.093c-5.647,0-10.24,4.593-10.24,10.24c0,5.647,4.593,10.24,10.24,10.24c5.647,0,10.24-4.593,10.24-10.24    C136.532,79.686,131.939,75.093,126.292,75.093z M126.292,88.747c-1.883,0-3.413-1.531-3.413-3.413s1.531-3.413,3.413-3.413    c1.882,0,3.413,1.531,3.413,3.413S128.174,88.747,126.292,88.747z"
                                      data-original="#000000" class="active-path" data-old_color="#ffffff" fill="#ffffff" />
                              </g>
                          </g>
                          <g>
                              <g>
                                  <path d="M75.092,75.093c-5.647,0-10.24,4.593-10.24,10.24c0,5.647,4.593,10.24,10.24,10.24c5.647,0,10.24-4.593,10.24-10.24    C85.332,79.686,80.739,75.093,75.092,75.093z M75.092,88.747c-1.882,0-3.413-1.531-3.413-3.413s1.531-3.413,3.413-3.413    s3.413,1.531,3.413,3.413S76.974,88.747,75.092,88.747z"
                                      data-original="#000000" class="active-path" data-old_color="#ffffff" fill="#ffffff" />
                              </g>
                          </g>
                          <g>
                              <g>
                                  <path d="M126.292,85.333h-0.263c-1.884,0-3.413,1.529-3.413,3.413c0,0.466,0.092,0.911,0.263,1.316v17.457    c0,12.233-9.953,22.187-22.187,22.187s-22.187-9.953-22.187-22.187V88.747c0-1.884-1.529-3.413-3.413-3.413    s-3.413,1.529-3.413,3.413v18.773c0,15.998,13.015,29.013,29.013,29.013s29.013-13.015,29.013-29.013V88.747    C129.705,86.863,128.176,85.333,126.292,85.333z"
                                      data-original="#000000" class="active-path" data-old_color="#ffffff" fill="#ffffff" />
                              </g>
                          </g>
                      </g>
                  </svg>
                  Shops
              </a>
              <a href="{{ route('page.comingsoon') }}" class="resp_menu__link">
                  <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px"
                      viewBox="0 0 512.001 512.001" style="enable-background:new 0 0 512.001 512.001;" xml:space="preserve" width="16px"
                      height="16px">
                      <g>
                          <g>
                              <g>
                                  <path d="M504.501,421.9c-4.143,0-7.5,3.358-7.5,7.5v50.113c0,0.696-0.566,1.263-1.263,1.263H467.24V371.199    c0-4.142-3.357-7.5-7.5-7.5s-7.5,3.358-7.5,7.5v109.578H397.17l14.905-16.397c2.302-2.533,3.387-6.13,2.904-9.623    c-0.011-0.075-0.022-0.149-0.035-0.224l-16.755-98.996l3.083-4.641l0.803,0.57c2.749,1.953,6.255,2.665,9.647,1.818    c3.331-0.833,6.122-3.074,7.656-6.146v-0.001l17.049-34.171l38.945,15.083c1.537,0.668,21.625,9.879,21.625,33.845v32.504    c0.001,4.143,3.358,7.501,7.501,7.501c4.143,0,7.5-3.358,7.5-7.5v-32.504c0-34.61-29.685-47.211-30.948-47.731    c-0.048-0.02-0.095-0.039-0.143-0.057l-66.317-25.684v-13.66c10.352-8.328,17.622-20.331,19.805-34.014    c5.163-0.648,9.953-2.715,13.928-6.134c5.7-4.902,8.971-12.035,8.971-19.568c0-6.013-2.087-11.767-5.824-16.337v-20.238    c0-39.606-32.222-71.827-71.826-71.827c-17.781,0-34.061,6.508-46.618,17.25c-0.162-5.747-2.219-11.223-5.804-15.607V88.051    c0-39.606-32.222-71.827-71.826-71.827c-39.606,0-71.827,32.222-71.827,71.827v20.765c-3.605,4.308-5.622,9.502-5.8,15.015    c-8.012-6.847-17.601-12.002-28.288-14.816c-4.012-1.056-8.109,1.337-9.164,5.343s1.337,8.109,5.343,9.164    c24.913,6.56,42.313,29.157,42.313,54.949v11.217c-0.324-0.067-1.364-0.246-1.404-0.252c-0.102-2.117-0.638-3.41-1.792-5.07    c-1.92-2.759-5.368-6.196-16.458-18.116c-3.952-4.245-10.344-5.024-15.196-1.852c-18.995,12.408-42.072,18.732-63.488,17.475    c-6.976-0.409-11.333,2.656-13.19,7.679c-0.166,0.027-2.021,0.401-2.124,0.426v-11.507c0-24.869,16.618-47.242,40.411-54.409    c3.966-1.194,6.213-5.378,5.019-9.345c-1.195-3.967-5.385-6.214-9.346-5.018c-14.47,4.358-27.477,13.432-36.622,25.548    c-9.463,12.534-14.464,27.481-14.464,43.225v20.766c-3.778,4.514-5.821,10-5.821,15.809c0,12.84,9.46,24.038,22.938,25.708    c2.208,13.363,9.458,26.178,20.9,34.891v12.781l-66.243,25.682c-0.047,0.018-0.094,0.037-0.141,0.056    C29.686,314.684,0,327.284,0,361.894v117.619c0,8.969,7.296,16.264,16.265,16.264h230.971c3.228,0,6.231-0.957,8.765-2.585    c2.532,1.628,5.536,2.585,8.765,2.585h230.972c8.968,0,16.264-7.295,16.264-16.264v-50.113    C512.001,425.258,508.644,421.9,504.501,421.9z M75.702,225.258c-4.397-1.596-7.206-5.663-7.206-10.213    c0-4.413,3.2-8.039,7.206-9.76V225.258z M422.379,307.525l-14.722,29.506c-0.002-0.002-16.353-11.615-16.585-11.78    c7.054-8.708,3.74-4.617,18.408-22.721L422.379,307.525z M380.23,314.803l-19.397-23.943v-7.195    c6.305,2.18,13.097,3.186,19.49,3.188c0.001,0,0.003,0,0.004,0h0.001c6.61-0.001,13.302-1.256,19.262-3.499v7.552L380.23,314.803z     M389.036,342.206l-2.759,4.153h-12.091l-2.759-4.153l8.805-6.253L389.036,342.206z M255.981,224.381l-19.397-23.943v-7.195    c6.28,2.171,13.065,3.186,19.49,3.188c0.001,0,0.003,0,0.004,0h0.001c6.598,0,13.289-1.251,19.262-3.498v7.551L255.981,224.381z     M264.786,251.786l-2.759,4.153h-12.091l-2.759-4.153l8.805-6.253L264.786,251.786z M196.918,149.614    c1.226,0.334,2.486,0.561,3.765,0.719c2.208,13.363,9.458,26.178,20.9,34.891v12.781l-15.301,5.931    c-0.887-1.863-1.996-3.618-3.312-5.227v-20.237C202.97,168.294,200.826,158.51,196.918,149.614z M295.836,141.672    c0,21.953-17.908,39.756-39.757,39.756c-24.651,0-41.125-19.105-41.125-39.756V106.54c23.683,0.948,48.286-6.071,68.401-18.591    c6.763,7.252,10.787,11.465,12.482,13.421C295.836,120.043,295.836,127.253,295.836,141.672z M310.147,150.328    c1.272-0.16,2.526-0.388,3.745-0.721c-3.896,8.84-6.076,18.6-6.076,28.865v20.765c-1.153,1.377-2.145,2.844-2.966,4.383    l-14.508-5.618v-13.66C300.693,176.013,307.964,164.01,310.147,150.328z M226.705,212.12l18.412,22.726l-12.556,8.918    c-0.007,0.005-0.012,0.01-0.019,0.015l-3.985,2.831l-14.723-29.505L226.705,212.12z M273.243,226.904l11.987-14.796l12.899,4.995    l-14.721,29.507l-3.994-2.836c-0.004-0.003-0.008-0.007-0.012-0.01l-12.579-8.935L273.243,226.904z M277.023,260.475l0.803,0.57    c2.804,1.992,6.323,2.65,9.647,1.818c3.331-0.833,6.122-3.074,7.656-6.146v-0.001l12.658-25.369    c4.349,5.36,10.639,8.62,17.117,9.429c2.288,14.34,10.039,26.578,20.929,34.87v12.78l-63.764,24.721l-8.13-48.03L277.023,260.475z     M350.954,302.541l18.412,22.726c-8.05,5.719-16.143,11.468-16.56,11.764l-14.723-29.505L350.954,302.541z M420.084,232.094    c0.002,21.417-17.391,39.756-39.756,39.756c-25.492,0-41.125-19.966-41.125-39.756v-35.132c23.514,0.939,48.149-5.987,68.4-18.591    c6.763,7.252,10.787,11.465,12.482,13.421C420.084,210.464,420.084,217.675,420.084,232.094z M442.294,215.046    c0,4.558-2.818,8.617-7.206,10.212v-20.403C439.624,206.507,442.294,210.652,442.294,215.046z M322.817,178.471    c0-31.334,25.493-56.826,56.826-56.826c31.334,0,56.825,25.493,56.825,56.826v11.217c-0.341-0.071-1.279-0.233-1.404-0.252    c-0.102-2.119-0.641-3.415-1.792-5.07c-1.971-2.831-5.381-6.209-16.458-18.116c-3.868-4.152-10.246-5.087-15.196-1.852    c-18.48,12.072-41.828,18.891-64.045,17.448c-6.241-0.409-10.762,2.647-12.633,7.707c-0.167,0.027-2.019,0.401-2.124,0.426    V178.471z M324.202,205.286v19.962c-4.41-1.59-7.206-5.668-7.206-10.203C316.995,210.632,320.193,207.008,324.202,205.286z     M318.043,124.625c0,3.158-1.367,6.145-3.75,8.194c-1.023,0.88-2.189,1.556-3.456,2.017v-20.403    C315.322,116.061,318.043,120.184,318.043,124.625z M255.393,31.224c31.334,0,56.826,25.492,56.826,56.826v11.217    c-0.339-0.07-1.279-0.233-1.404-0.252c-0.111-2.297-0.773-3.709-2.124-5.536c-2.008-2.718-5.71-6.455-16.126-17.651    c-3.933-4.223-10.321-5.038-15.196-1.852c-19.516,12.75-43.191,18.955-64.65,17.418c-5.25-0.397-10.193,2.775-12.027,7.736    c-0.167,0.027-2.02,0.401-2.124,0.426V88.051h-0.001C198.567,56.716,224.06,31.224,255.393,31.224z M199.951,114.864v19.973    c-4.397-1.596-7.206-5.663-7.206-10.213C192.745,120.236,195.92,116.597,199.951,114.864z M186.587,204.855    c4.196,1.527,6.891,5.188,7.158,9.539c0.336,4.838-2.637,9.213-7.158,10.855V204.855z M185.892,240.774    c7.074-0.885,13.567-4.613,17.825-10.348l13.118,26.29c0,0,0,0,0.001,0.001c1.533,3.073,4.324,5.313,7.655,6.146    c3.293,0.823,6.816,0.193,9.647-1.818l0.803-0.57l3.083,4.641l-8.127,48.017l-63.804-24.711v-13.654h-0.001    C176.715,266.203,183.76,254.101,185.892,240.774z M131.73,314.803l-19.397-23.943v-7.195c6.28,2.171,13.066,3.186,19.491,3.187    c0.001,0,0.003,0,0.004,0c6.706,0,13.386-1.288,19.262-3.498v7.552L131.73,314.803z M140.536,342.206l-2.759,4.153h-12.091    l-2.759-4.153l8.805-6.253L140.536,342.206z M160.979,302.53l12.9,4.996l-14.722,29.505l-3.994-2.836    c-0.004-0.003-0.008-0.007-0.012-0.01l-12.579-8.934L160.979,302.53z M90.703,232.094v-35.132    c23.809,0.951,48.428-6.161,68.4-18.591c6.699,7.184,10.785,11.463,12.482,13.421c0,18.673,0,25.884,0,40.302    c0,21.813-17.785,39.756-39.757,39.756C107.365,271.85,90.703,252.963,90.703,232.094z M102.455,302.541l18.412,22.726    l-12.556,8.918c-0.007,0.005-0.012,0.01-0.019,0.015l-3.985,2.831l-14.723-29.505L102.455,302.541z M59.763,480.778V371.199    c0-4.142-3.357-7.5-7.5-7.5s-7.5,3.358-7.5,7.5v109.578H16.265c-0.698,0-1.264-0.567-1.264-1.263V361.894    c0-24.089,20.297-33.271,21.611-33.839l38.921-15.089l17.051,34.171c0,0,0,0,0.001,0.001c1.533,3.073,4.324,5.313,7.656,6.146    c3.293,0.823,6.816,0.193,9.647-1.818l0.803-0.57l3.083,4.641l-16.755,98.996c-0.013,0.074-0.024,0.149-0.035,0.224    c-0.482,3.491,0.603,7.088,2.906,9.625l14.903,16.395H59.763z M112.073,455.481l15.929-94.121h7.459l15.93,94.121l-19.659,21.627    L112.073,455.481z M248.499,479.513c0,0.697-0.566,1.264-1.263,1.264H218.74V371.199c0-4.142-3.357-7.5-7.5-7.5    c-4.143,0-7.5,3.358-7.5,7.5v109.578H148.67l14.905-16.397c2.302-2.533,3.387-6.13,2.904-9.623    c-0.011-0.075-0.022-0.149-0.035-0.224l-16.755-98.996l3.083-4.641l0.803,0.57c2.007,1.426,4.383,2.17,6.794,2.17    c4.399,0,8.496-2.465,10.51-6.498v-0.001l17.049-34.17l38.945,15.083c11.352,4.938,21.625,17.673,21.625,33.844V479.513z     M256,334.64c-3.107-5.006-7.243-9.638-12.229-13.591l8.481-50.109h7.459l8.485,50.135C263.077,325.14,258.995,329.814,256,334.64    z M308.262,480.778L308.262,480.778V371.199c0-4.142-3.357-7.5-7.5-7.5s-7.5,3.358-7.5,7.5v109.578h-28.497    c-0.697,0-1.264-0.567-1.264-1.263V361.894c0-8.916,2.796-17.128,8.752-24.027c3.971-4.555,9.238-8.243,12.86-9.812l38.921-15.09    l17.051,34.172c0,0,0,0,0.001,0.001c1.533,3.073,4.324,5.313,7.655,6.146c3.293,0.823,6.816,0.193,9.647-1.818l0.803-0.57    l3.083,4.641l-16.755,98.996c-0.013,0.074-0.024,0.149-0.035,0.224c-0.482,3.491,0.603,7.088,2.906,9.625l14.903,16.395H308.262z     M360.572,455.482l15.93-94.121h7.459l15.93,94.121l-19.66,21.627L360.572,455.482z"
                                      data-original="#000000" class="active-path" data-old_color="#FCFAFA" fill="#FFFFFF" />
                              </g>
                          </g>
                      </g>
                  </svg>
                  Jobs
              </a>
              <!--
              <a href="patnership.html" class="resp_menu__link">
                  <svg width="16px" height="16px" viewBox="0 0 15 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M0 8.09375C0 8.96624 0.59877 9.69318 1.40625 9.90283V16.0625H0.46875C0.210557 16.0625 0 16.2721 0 16.5312C0 16.7904 0.210586 17 0.46875 17H14.5312C14.7913 17 15 16.7904 15 16.5312C15 16.2721 14.7913 16.0625 14.5312 16.0625H13.5938V9.90283C14.4012 9.69318 15 8.96624 15 8.09375V7.625H0V8.09375ZM9.375 9.96875C10.4114 9.96875 11.25 9.12922 11.25 8.09375C11.25 8.96624 11.8488 9.69318 12.6562 9.90283V16.0625H5.625V9.96875C6.66138 9.96875 7.5 9.12922 7.5 8.09375C7.5 9.12922 8.34047 9.96875 9.375 9.96875ZM5.15625 9.90283V16.0625H2.34375V9.90283C3.15126 9.69318 3.75 8.96624 3.75 8.09375C3.75 8.96624 4.34877 9.69318 5.15625 9.90283ZM13.125 3.40625C13.1799 4.72835 13.3297 5.22158 12.5 5.22158C11.6703 5.22158 11.9224 4.66268 11.826 3.40625H1.875L0 7.15625H15L14.0625 5.28125L13.125 3.40625ZM3.96059 4.44813L3.02309 6.32313C2.98096 6.40555 2.89857 6.45315 2.8125 6.45315C2.7777 6.45315 2.74201 6.44583 2.70811 6.42843C2.59274 6.37074 2.54514 6.22977 2.60373 6.11439L3.54123 4.23939C3.59982 4.12402 3.73989 4.07735 3.85526 4.1341C3.97157 4.19179 4.01733 4.33276 3.96059 4.44813ZM5.83559 4.44813L4.89809 6.32313C4.85596 6.40555 4.77357 6.45315 4.6875 6.45315C4.6527 6.45315 4.61701 6.44583 4.58312 6.42843C4.46774 6.37074 4.42014 6.22977 4.47873 6.11439L5.41623 4.23939C5.47482 4.12402 5.61583 4.07735 5.73026 4.1341C5.84657 4.19179 5.89233 4.33276 5.83559 4.44813ZM7.73437 6.21875C7.73437 6.34877 7.63002 6.45312 7.5 6.45312C7.37092 6.45312 7.26562 6.34874 7.26562 6.21875V4.34375C7.26562 4.21373 7.37092 4.10938 7.5 4.10938C7.63002 4.10938 7.73437 4.21376 7.73437 4.34375V6.21875ZM10.426 6.42383C10.3894 6.44398 10.351 6.45312 10.3125 6.45312C10.2301 6.45312 10.1496 6.40918 10.1074 6.33137L9.08203 4.45637C9.01978 4.34284 9.06187 4.20002 9.17543 4.13867C9.28714 4.07642 9.43178 4.1167 9.49403 4.23113L10.5194 6.10613C10.5798 6.21966 10.5396 6.36248 10.426 6.42383ZM12.2919 6.4284C12.2589 6.4458 12.2241 6.45312 12.1875 6.45312C12.1014 6.45312 12.019 6.40552 11.9788 6.32311L11.0413 4.44811C10.9827 4.33273 11.0303 4.19176 11.1456 4.13407C11.2591 4.07639 11.402 4.12309 11.4606 4.23937L12.3981 6.11437C12.4548 6.22974 12.409 6.37074 12.2919 6.4284ZM3.98437 13.3214V12.2411C3.84703 12.1587 3.75 12.0159 3.75 11.8438C3.75 11.5847 3.96059 11.375 4.21875 11.375C4.47876 11.375 4.6875 11.5847 4.6875 11.8438C4.6875 12.0159 4.59138 12.1587 4.45312 12.2411V13.3214C4.59138 13.4038 4.6875 13.5467 4.6875 13.7188C4.6875 13.9779 4.47876 14.1875 4.21875 14.1875C3.96056 14.1875 3.75 13.9779 3.75 13.7188C3.75 13.5466 3.84706 13.4038 3.98437 13.3214ZM8.21596 12.4782L7.88455 12.1468L9.21023 10.8211L9.54164 11.1525L8.21596 12.4782ZM8.21596 13.8039L7.88455 13.4725L10.5359 10.8211L10.8673 11.1525L9.54164 12.4782L8.21596 13.8039ZM10.5359 12.1468L10.8673 12.4782L9.54161 13.8039L9.21021 13.4725L10.5359 12.1468Z"
                          fill="#FFFFFF" />
                      <path d="M1.875 2.46875C1.875 2.20965 2.08559 2 2.34375 2H9.72145C9.4009 2.37104 9.40406 2.5704 9.72145 2.9375H2.34375C2.08559 2.9375 1.875 2.72785 1.875 2.46875Z"
                          fill="#FFFFFF" />
                      <path d="M14.7287 2.15115H12.8488V0.271317C12.8488 0.185683 12.7325 0 12.5 0C12.2674 0 12.1512 0.185693 12.1512 0.271317V2.15116H10.2713C10.1857 2.15115 10 2.26743 10 2.49998C10 2.73253 10.1857 2.84882 10.2713 2.84882H12.1512V4.72866C12.1512 4.81428 12.2674 4.99998 12.5 4.99998C12.7326 4.99998 12.8488 4.81428 12.8488 4.72866V2.84882H14.7287C14.8143 2.84882 15 2.73255 15 2.49998C15 2.26741 14.8143 2.15115 14.7287 2.15115Z"
                          fill="#FFFFFF" />
                  </svg>
                  Create Store
              </a>
              -->
              @if(Auth::check())
              <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-min').submit();" class="resp_menu__link">
                  <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px"
                      viewBox="0 0 490.3 490.3" style="enable-background:new 0 0 490.3 490.3;" xml:space="preserve" width="16px" height="16px">
                      <g>
                          <g>
                              <g>
                                  <path d="M0,121.05v248.2c0,34.2,27.9,62.1,62.1,62.1h200.6c34.2,0,62.1-27.9,62.1-62.1v-40.2c0-6.8-5.5-12.3-12.3-12.3    s-12.3,5.5-12.3,12.3v40.2c0,20.7-16.9,37.6-37.6,37.6H62.1c-20.7,0-37.6-16.9-37.6-37.6v-248.2c0-20.7,16.9-37.6,37.6-37.6h200.6    c20.7,0,37.6,16.9,37.6,37.6v40.2c0,6.8,5.5,12.3,12.3,12.3s12.3-5.5,12.3-12.3v-40.2c0-34.2-27.9-62.1-62.1-62.1H62.1    C27.9,58.95,0,86.75,0,121.05z"
                                      data-original="#000000" class="active-path" data-old_color="#ffffff" fill="#ffffff" />
                                  <path d="M385.4,337.65c2.4,2.4,5.5,3.6,8.7,3.6s6.3-1.2,8.7-3.6l83.9-83.9c4.8-4.8,4.8-12.5,0-17.3l-83.9-83.9    c-4.8-4.8-12.5-4.8-17.3,0s-4.8,12.5,0,17.3l63,63H218.6c-6.8,0-12.3,5.5-12.3,12.3c0,6.8,5.5,12.3,12.3,12.3h229.8l-63,63    C380.6,325.15,380.6,332.95,385.4,337.65z"
                                      data-original="#000000" class="active-path" data-old_color="#ffffff" fill="#ffffff" />
                              </g>
                          </g>
                      </g>
                  </svg>


                  Log Out
              </a>
              <form id="logout-form-min" action="{{ route('logout') }}" method="POST" style="display: none;">
                  {{ csrf_field() }}
              </form>
            @endif
          </nav>
    </div>
    <!-- end -->
    @include("blogetc::partials.auth_modals")
    <script src="{{ asset('themes/' . current_theme() . '/js/nav.js') }}"></script>
    <script src="{{ asset('themes/' . current_theme() . '/js/blog.js') }}"></script>
    <script src="{{ asset('themes/' . current_theme() . '/js/modal/bootstrap.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/14.0.8/js/intlTelInput-jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/14.0.8/js/intlTelInput.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/14.0.8/js/utils.js"></script>
    @include("blogetc::partials.auth_js")

</body>

</html>
