<!-- <div class="d-flex justify-content-center mb-3">
    <button type="button" class="btn btn-outline-primary" id="email-login-btn">Email Login</button>
    <button type="button" class="btn btn-outline-secondary ms-2" id="otp-login-btn">Mobile OTP Login</button>
</div> -->

<!-- <form id="email-login-form" class="bravo-form-login" method="POST" action="{{ route('login') }}">
    <input type="hidden" name="redirect" value="{{request()->query('redirect')}}">
    @csrf
    <div class="form-group">
        <input type="text" class="form-control" name="email" autocomplete="off" placeholder="{{__('Email address')}}">
        <i class="input-icon icofont-mail"></i>
        <span class="invalid-feedback error error-email"></span>
    </div>
    <div class="form-group">
        <input type="password" class="form-control" name="password" autocomplete="off"  placeholder="{{__('Password')}}">
        <i class="input-icon icofont-ui-password"></i>
        <span class="invalid-feedback error error-password"></span>
    </div>
    <div class="form-group">
        <div class="d-flex justify-content-between">
            <label for="remember-me" class="mb0">
                <input type="checkbox" name="remember" id="remember-me" value="1"> {{__('Remember me')}} <span class="checkmark fcheckbox"></span>
            </label>
            <a href="{{ route("password.request") }}">{{__('Forgot Password?')}}</a>
        </div>
    </div>
    @if(setting_item("user_enable_login_recaptcha"))
        <div class="form-group">
            {{recaptcha_field($captcha_action ?? 'login')}}
        </div>
    @endif
    <div class="error message-error invalid-feedback"></div>
    <div class="form-group">
        <button class="btn btn-primary form-submit" type="submit">
            {{ __('Login') }}
            <span class="spinner-grow spinner-grow-sm icon-loading" role="status" aria-hidden="true"></span>
        </button>
    </div>
    @if(setting_item('facebook_enable') or setting_item('google_enable') or setting_item('twitter_enable'))
        <div class="advanced">
            <p class="text-center f14 c-grey">{{__('or continue with')}}</p>
            <div class="row">
                @if(setting_item('facebook_enable'))
                    <div class="col-xs-12 col-sm-4">
                        <a href="{{url('/social-login/facebook')}}"class="btn btn_login_fb_link" data-channel="facebook">
                            <i class="input-icon fa fa-facebook"></i>
                            {{__('Facebook')}}
                        </a>
                    </div>
                @endif
                @if(setting_item('google_enable'))
                    <div class="col-xs-12 col-sm-4">
                        <a href="{{url('social-login/google')}}" class="btn btn_login_gg_link" data-channel="google">
                            <i class="input-icon fa fa-google"></i>
                            {{__('Google')}}
                        </a>
                    </div>
                @endif
                @if(setting_item('twitter_enable'))
                    <div class="col-xs-12 col-sm-4">
                        <a href="{{url('social-login/twitter')}}" class="btn btn_login_tw_link" data-channel="twitter">
                            <i class="input-icon fa fa-twitter"></i>
                            {{__('Twitter')}}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    @endif
    @if(is_enable_registration())
        <div class="c-grey font-medium f14 text-center"> {{__('Do not have an account?')}} <a href="" data-target="#register" data-toggle="modal">{{__('Sign Up')}}</a></div>
    @endif
</form> -->


<!-- Mobile OTP Login Form (Initially Hidden) -->
<form id="otp-login-form" class="bravo-form-otplogin" method="POST" action="">
     @csrf  
    <div class="form-group">
      <!-- <h4>Welcome to our Login {{ setting_item('site_title') }}</h4> -->
        <div class="input-group">
 	 
           <span class="input-group-text">
                <img src="https://upload.wikimedia.org/wikipedia/en/4/41/Flag_of_India.svg" alt="India Flag" style="width: 20px; height: 15px; margin-right: 5px;">
                +91
            </span>
            <input type="text" class="form-control" id="mobile-number" autocomplete="off" 
                placeholder="Enter Mobile Number" maxlength="10" pattern="\d{10}" title="Enter a 10-digit mobile number" required>
		 <i class="input-icon icofont-ui-call"></i>
        </div>
       
        <!-- <span class="invalid-feedback error" id="error-mobile"></span> -->
         <small class="text-danger d-block mt-1" id="error-mobile"></small>
    </div>
    <div class="form-group text-center">
        <button type="button" class="btn btn-primary" id="send-otp">Continue</button>
    </div>
 	<span class="text-success" id="otp-success-mobile"></span>
    <div class="form-group d-none" id="otp-section">
        <input type="text" class="form-control" id="otp-input" autocomplete="off" 
            placeholder="Enter OTP" maxlength="6" pattern="\d{6}" title="Enter a 6-digit OTP" required>
        <i class="input-icon icofont-verification-check"></i>
        <span class="invalid-feedback error" id="otp-error"></span>
        <button class="btn btn-success mt-2 center" id="otplogin" type="submit"> {{ __('Verify & Login') }}
             <span class="spinner-grow spinner-grow-sm icon-loading" role="status" aria-hidden="true"></span>
        </button>
    </div>
   <div>
     <span class="text-center f10 c-grey"> {{__('By proceeding, you agree to Travels2020 Privacy Policy, User Agreement and Terms of Service')}}</span>    </div>
</form>
