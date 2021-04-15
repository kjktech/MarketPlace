<!DOCTYPE html>
<html lang="en">

<head>
    <meta id="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='shortcut icon' type='image/x-icon' href="{{ asset('images/favicon.ico') }}" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css?family=Lato:100,300,300i,400');
        @import url({{ asset('themes/' ~ current_theme() ~ '/css/vendor.css')}});
        @import url({{ asset('themes/' ~  current_theme()  ~ '/css/footer.css')}});
    </style>
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <title>Vendor</title>
    <style>
    .ui-state-default, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default, .ui-button, html .ui-button.ui-state-disabled:hover, html .ui-button.ui-state-disabled:active {
      border: 1px solid #fff;
      background: linear-gradient(90deg, #E6B712 31.25%, #CAAA3B 88.19%);
      font-weight: normal;
      color: #454545;
      box-shadow: 2px 1px 1px 0px rgba(0,0,0,.6);
     }
    .ui-slider .ui-slider-handle {
      position: absolute;
      z-index: 2;
      width: 1.2em;
      height: 1.2em;
      border-radius: 50%;
      cursor: default;
      -ms-touch-action: none;
      background: linear-gradient(90deg, #E6B712 31.25%, #CAAA3B 88.19%);
      touch-action: none;
      -webkit-border-radius: 50%;
      -moz-border-radius: 50%;
      -ms-border-radius: 50%;
      -o-border-radius: 50%;
     }
    </style>
  </head>

   <body>
    <header>

      {% block nav %}
          {% include 'layouts.base_nav.twig' %}
      {% endblock %}
      {% block megamenu %}
          {% include 'layouts.base_mega_menu.twig' %}
      {% endblock %}

       {% block hero %}
        <div class="hero">
            <div class="hero__content-container">
                <div class="hero__image-part">
                  {% if store.carousel | length > 0 %}
                    <img class="hero__first-image" height="190" src="{{ store.carousel.0 }}">
                    <!--
                    <img class="hero__second-image" src="{{ store.carousel.0 }}">
                    <img class="hero__third-image" src="{{ store.carousel.0 }}">
                    -->
                  {% else %}
                  <img class="hero__first-image" height="190" src="{{ asset('themes/' ~ current_theme() ~ '/images/vendor/hero-pics.svg')}}">
                  <!--
                  <img class="hero__second-image" src="{{ asset('themes/' ~ current_theme() ~ '/images/vendor/hero-pics.svg')}}">
                  <img class="hero__third-image" src="{{ asset('themes/' ~ current_theme() ~ '/images/vendor/hero-pics.svg')}}">
                  -->
                  {% endif %}
                </div>
                <div class="hero__text-part">
                    <div class="hero__text-container">
                        <h1 class="hero__text-title">
                            {{ store.name }}
                        </h1>
                        <p class="hero__text-content"></p>
                    </div>
                </div>
            </div>

        </div>

        <div class="lower-nav">
            <div class="row lower-nav__container">
                <div class="lower-nav__left-nav">
                    <ul class="lower-nav-list__container">
                        <li class="lower-nav__list lower-nav__list--crumbs">
                            <a href="" class="lower-nav__link">Home&nbsp;&nbsp;</a>
                        </li>
                        <li class="lower-nav__list">
                            <a href="" class="lower-nav__link">{{ store.name }}</a>
                        </li>
                    </ul>
                </div>

                <div class="lower-nav__sort-container">
                    <span for="" class="sort">Sort By</span>
                    <div class="custom-select" style="width:200px;">
                        <select>
                            <option value="0">Highest Price</option>
                            <option value="1">Highest Price</option>
                            <option value="2">Lowest Price</option>
                            <option value="3">Newest</option>
                            <option value="4">Most Popular</option>
                            <option value="5">Best Rating</option>
                        </select>
                    </div>
                </div>

            </div>
        </div>
       {% endblock %}
    </header>

    {% block content %}

    {% endblock %}



    {% block footer %}
        {% set new_res_img = {'foo': 'bar'} %}
        {% include 'layouts.footer_latest.twig' %}
    {% endblock %}
    {% block scripts %}

    {% endblock %}

</body>
</html>
