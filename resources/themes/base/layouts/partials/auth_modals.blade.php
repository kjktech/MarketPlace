<!-- login modal -->
<div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="signuponelabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <img src="{{ asset('themes/base/images/signup-background.png') }}" alt="" class="signup__background">
            <div class="modal-body signup">
                <div class="signup__heading">
                    <div class="signup__logo">
                        <img src="{{ asset('themes/base/images/market-logo.svg') }}" alt="" class="img-fluid">
                    </div>
                    <h3 class="signup__heading-text">Welcome Back!</h3>
                    <p class="signup__heading-subtext">Login to continue</p>
                    <div style="display: none;" class="loader loader-gif">
                      <img src="{{ asset('images/blog/ajax-loader.gif') }}" class="gif__loader" />
                    </div>
                    <div style="display: none; color: red;" class="loader loader-msg">
                      Unable to login
                    </div>
                </div>

                <div class="signup__form">
                    <form action="" id="modal-login-form">
                        {{ csrf_field() }}
                        <div style="margin-bottom: 27px;">
                          <input style="margin-bottom: 0px;" required type="email" name="email" class="signup__form-input form-control" placeholder="Email">
                          <span class="form-error email-login-error"></span>
                        </div>
                        <div style="margin-bottom: 27px;">
                          <input style="margin-bottom: 0px;" required type="password" name="password" class="signup__form-input form-control" placeholder="Password">
                          <span class="form-error password-login-error"></span>
                        </div>
                        <input type="checkbox" name="remember" class="signup__form-input-check">
                        <span class="signup__form-subscribe">Remember me</span>
                        <span class="login__forgot-password"><a style="cursor:pointer;">Forgot Password?</a></span>
                        <button id="modal-login-btn" type="submit" class="btn btn-primary signup__form-button mt-4">Login</button>
                    </form>
                    <!--
                    <div class="login__social-wrapper">
                        <p>Social Media Login</p>
                        <div class="login__social-button-wrapper">
                            <div class="login__social-button facebook">
                                <svg class="icon icon-facebook-login">
                                    <use xlink:href="#icon-facebook-login"></use>
                                </svg>
                                <span>Connect with Facebook</span>
                            </div>
                            <div class="login__social-button linkedin">
                                <svg class="icon icon-linkedin-login">
                                    <use xlink:href="#icon-linkedin-login"></use>
                                </svg>
                                <span>Connect with Linkedin</span>
                            </div>
                            <div class="login__social-button google">
                                <svg class="icon icon-google-plus-login">
                                    <use xlink:href="#icon-google-plus-login"></use>
                                </svg>
                                <span>Connect with Google</span>
                            </div>
                        </div>
                    </div>
                   -->
                    <p class="signup__form-already-in">No Account Yet? <a href="#signup-one" data-toggle="modal"
                            data-dismiss="modal">Create One</a></p>
                  </div>

                <img src="{{ asset('themes/base/icons/afiaanyi icon.svg') }}" alt="" class="interest__individual-cover-img">
            </div>
        </div>
    </div>
</div>
<!-- signup modal -->
<div class="modal fade" id="signup-one" tabindex="-1" role="dialog" aria-labelledby="signuponelabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
       <div class="modal-content">
           <img src="{{ asset('themes/base/images/signup-background.png') }}" alt="" class="signup__background">
           <div class="modal-body signup">
               <div class="signup__heading">
                   <div class="signup__logo">
                       <img src="{{ asset('themes/base/images/market-logo.svg') }}" alt="" class="img-fluid">
                   </div>
                   <h3 class="signup__heading-text">Create an Account</h3>
                   <p class="signup__heading-subtext">Get access to thousands of businesses</p>
                   <div style="display: none;" class="loader loader-account-gif">
                     <img src="{{ asset('images/blog/ajax-loader.gif') }}" class="gif__loader" />
                   </div>
                   <div style="display: none; color: red;" class="loader loader-account-msg">
                     Unable to create account try again
                   </div>
                </div>

               <div class="signup__form">

                   <form id="modal-account-form" action="sign up">
                      {{ csrf_field() }}
                       <input required type="text" name="name" class="signup__form-input form-control account-input" placeholder="Full Name">
                       <span class="form-error name-error"></span>
                       <input style="margin-top: 25px;" required type="email" name="email" class="signup__form-input form-control account-input" placeholder="Email">
                       <span class="form-error email-error"></span>
                       <input style="margin-top: 20px !important; text-indent: 30px;" id="phone" required type="text" name="phone" class="signup__form-input form-control account-input">
                       <input type="hidden" id="myFormCheck" required value=""/>

                       <input style="margin-top: 30px;"  required type="password" name="password" class="signup__form-input form-control account-input" placeholder="Password">
                       <span class="form-error password-error"></span>
                       <input style="margin-top: 25px;" required type="password" name="password_confirmation" class="signup__form-input form-control account-input" placeholder="Confirm Password">
                       <button id="modal-account-btn" class="btn btn-primary signup__form-button">Create Account</button>
                   </form>
                   <!-- <p class="signup__form-already-in">Already Registered? <a href="#">Sign in</a></p> -->
               </div>

               <img src="{{ asset('themes/base/icons/afiaanyi icon.svg') }}" alt="" class="interest__individual-cover-img">
           </div>
       </div>
   </div>
</div>
<!-- login modal -->
<div class="modal fade" id="verifyemail" tabindex="-1" role="dialog" aria-labelledby="signuponelabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
       <div class="modal-content">
           <img src="{{ asset('themes/base/images/verifyemail-background.png') }}" alt="" class="signup__background">
           <div class="modal-body verifyemail">
             <div class="verification__heading">
                 Check Your Mail
             </div>
             <div class="center-info">
               <div class="verifcation__info">
                   <p class="verify-details">We have sent a verification email to your email address.</p>
                   <p class="verify-details">Check and follow the link inside to verify your email.</p>
               </div>
               <div class="verify-details verification__footer">
                   Not in your inbox? Check your spam folder
               </div>
             </div>
             <img src="{{ asset('themes/base/icons/afiaanyi icon.svg') }}" alt="" class="interest__individual-cover-img">
           </div>
       </div>
   </div>
</div>
<!-- Recover pass modal -->
<div class="modal fade" id="recover" tabindex="-1" role="dialog" aria-labelledby="recoveronelabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <img src="{{ asset('themes/base/images/signup-background.png') }}" alt="" class="signup__background">
            <div class="modal-body signup">
                <div class="signup__heading">

                    <br><br><br><br>
                    <h3 class="signup__heading-text">Forgot Password</h3>
                    <br><br><br>
                    <p style="color: black; text-align: left;" class="signup__heading-subtext">Please enter the email
                       registered with this account <br>  and we will help you recover
                       your account
                     </p>
                    <div style="display: none;" class="loader loader-recover-gif">
                      <img src="{{ asset('images/blog/ajax-loader.gif') }}" class="gif__loader" />
                    </div>
                    <div style="display: none; color: red;" class="loader loader-recover-msg">
                      Unable to send recovery link
                    </div>
                    <div style="display: none; color: green;" class="loader loader-recover-success-msg">
                      A password link has been sent to your email address
                    </div>
                </div>

                <div class="recover__form">
                    <form action="" id="modal-recover-form">
                        {{ csrf_field() }}
                        <input id="recover-email" style="margin-bottom: 0px;" required type="email" name="email" class="signup__form-input form-control" placeholder="Email">
                        <br>
                        <div data-dismiss="modal" style="cursor:pointer; width: 100%; text-align: right; font-size: 12px; text-decoration-color: #E6B712 !important; text-decoration: underline;">Back></div>

                        <button id="modal-recovery-btn" type="submit" class="btn btn-primary signup__form-button mt-4">Request Password Reset</button>
                    </form>

                  </div>
                <img src="{{ asset('themes/base/icons/afiaanyi icon.svg') }}" alt="" class="interest__individual-cover-img">
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="registerbusiness" tabindex="-1" role="dialog" aria-labelledby="registermodallabel" aria-hidden="true">
   <div class="modal-dialog" role="document">
       <div class="modal-content">
           <img src="{{ asset('themes/base/images/signup-background.png') }}" alt="" class="signup__background">
           <div style="height: 330px;" class="modal-body verifyemail">
             <div class="signup__heading">
                 <div class="signup__logo">
                     <img src="{{ asset('themes/base/images/market-logo.svg') }}" alt="" class="img-fluid">
                 </div>

             </div>
             <div style="overflow: visible; padding-top: 0px;" class="center-info">
               <div class="verifcation__info">
                   <p class="verify-details">You need to login to register a business</p>
                   <p class="verify-details">Join the thousands of business connecting with their customers.</p>
                   <br>
                   <div data-dismiss="modal" style="cursor:pointer; width: 100%; text-align: right; font-size: 12px; text-decoration-color: #E6B712 !important; text-decoration: underline;">Back></div>
               </div>
             </div>
             <img src="{{ asset('themes/base/icons/afiaanyi icon.svg') }}" alt="" class="interest__individual-cover-img">
           </div>
       </div>
   </div>
</div>
