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
                    <img width="80px;" src="{{ asset('themes/base/images/commodo-seal.png') }}" />
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
                    <li class="colored-list">SELL ON AFIANANYI</li>
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
                        <a href="{{ route('page.comingsoon') }}">EMS parcel</a>
                    </li>
                </ul>
            </div>
            <div class="left-space">
                <ul>
                    <li class="colored-list big-para">DOWNLOAD AND CONNECT WITH US</li>
                </ul>
                <div class="mobile-app">

                </div>

                <a href=""><img style="width: 122px;" src="{{ asset('images/blog/ios.svg') }}">
                </a>&nbsp;&nbsp;
                <a href=""><img style="width: 122px;" src="{{ asset('images/blog/android.svg') }}"></a>

                <p class="follow__p">Follow us:
                    <span>
                        <a href="https://www.facebook.com/afiaanyi/"><img class="icon-small" src="{{ asset('images/blog/brands/facebook-white.svg') }}" width="22px"
                                height="22px" alt="facebook"></a>
                    </span>
                    <span>
                        <a href="https://www.instagram.com/afiaanyi/"><img class="icon-small" src="{{ asset('images/blog/brands/instagram-white.svg') }}" width="22px"
                                height="22px" alt="instagram"></a>
                    </span>
                    <span>
                        <a href="https://www.linkedin.com/company/afiaanyi/"><img class="icon-small" src="{{ asset('images/blog/brands/linkedin-white.svg') }}" width="22px"
                                height="22px" alt="linkedin"></a>
                    </span>
                    <span>
                        <a href="https://twitter.com/afiaanyi"><img class="icon-small" src="{{ asset('images/blog/brands/twitter-circular-button-white.svg') }}"
                                width="22px" height="22px" alt="twitter"></a>
                    </span>
                </p>
                <p class="powered">Powered by
                    <img class="logo-by" src="{{ asset('themes/base/images/logo-by.svg') }}">
                </p>
            </div>
        </div>


        <img class="footer-last-img" src="{{ asset('images/blog/awka.svg') }}">
        <!--<img class="footer-last-img" src="{{ asset('images/blog/onitsha.svg') }}">-->
        <img class="footer-last-img" src="{{ asset('images/blog/nccima.svg') }}">
        <img class="footer-last-img" src="{{ asset('images/blog/oni.svg') }}">

        <p class="copy">Copyright (c) 2018 Afiaanyi. All rights reserved</p>

</section>
