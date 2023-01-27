@extends('layouts.layoutfront')
@section('title', env('APP_NAME').' - Register')
@section('header_display', 'display:none')
@section('alert_display', 'display:none')
@section('content')

    @if($isMobile)
        <!-- <div class="mt-5"> <br><br></div> -->
    @endif
    <!-- SIGNUP SECTION BEGIN -->
    <section class="signup-sec body-img">
        <div class="container">
            <div class="row">
                <div class="col-md-1 col-sm-1 col-12">
                </div>
                <div class="col-md-10 col-sm-10 col-12">
                    @if(session('success'))
                        <div class="alert alert-success mt-5">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger mt-5">
                            {{ session('error') }}
                        </div>
                    @endif
                    <form class="registerForm" action="{{ route('register') }}"
                          method="post">
                        {{ csrf_field() }}
                        <div class="main-box">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-12">
                                    <h4 class="main-box-heading">Sign up for Prezziez</h4>
                                    <p class="main-box-text">Please make sure all information is accurate to
                                        avoid any potential delays or cancellations.</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-12">
                                    <div class="field-text">
                                        <input data-name="Full Name" type="text" id="name" placeholder="Full Name"
                                               class="field-input {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                               value="{{ old('name') }}" name="name" required>
                                        {{-- <div class="input-icon">
                                            <i class="fa fa-info-circle" aria-hidden="true"></i>
                                        </div> --}}
                                    </div>
                                    @if($errors->has('name'))
                                        <div class="validation_display validation_neg_margin">
                                            {{ $errors->first('name') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6 col-sm-12 col-12">
                                    <div class="field-text">
                                        <input type="text" id="username" placeholder="Username" data-name="Username"
                                               class="field-input {{ $errors->has('username') ? 'is-invalid' : '' }}"
                                               value="{{ old('username') }}" name="username"  required>
                                        <div class="input-icon">
                                            <a href="javascript:;" class="tooltip-color" data-toggle="tooltip" data-placement="top" tabindex="-1" title="This is the searchable name that will allow users to find your wishlists"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                                        </div>
                                    </div>
                                    @if($errors->has('username'))
                                        <div class="validation_display validation_neg_margin">
                                            {{ $errors->first('username') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-12">
                                    <div class="field-text">
                                        <input type="email" id="email" data-name="email" placeholder="Enter email address"
                                               class="field-input {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                               value="{{ old('email') }}" name="email"
                                               required>
                                    </div>
                                    @if($errors->has('email'))
                                        <div class="validation_display validation_neg_margin">
                                            {{ $errors->first('email') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6 col-sm-12 col-12">
                                    <div class="field-text">
                                        <input type="text" id="displayName" data-name="Display name" placeholder="Display Name"
                                               class="field-input {{ $errors->has('displayName') ? 'is-invalid' : '' }}"
                                               value="{{ old('displayName') }}" name="displayName" required >
                                        <div class="input-icon">
                                            <a href="javascript:;" class="tooltip-color" data-toggle="tooltip" data-placement="top" tabindex="-1" title="This is the public name that will appear on your profile"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                                        </div>
                                    </div>
                                    @if($errors->has('displayName'))
                                        <div class="validation_display validation_neg_margin">
                                            {{ $errors->first('displayName') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-12">
                                    <div class="field-text">
                                        <input type="password" id="password" data-name="password" placeholder="Password"
                                               class="field-input {{ $errors->has('password') ? 'is-invalid' : '' }}"
                                               name="password" value="" required>
                                        <div class="input-icon">
                                            <a href="javascript:;" class="showPass" tabindex="-1">
                                                <i class="fa fa-eye" aria-hidden="true" ></i></a>
                                            <a href="javascript:;" class="tooltip-color" data-toggle="tooltip" data-placement="top" title="Must have at least 8 characters and contain a number and special character"  tabindex="-1"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                                        </div>
                                    </div>
                                    @if($errors->has('password'))
                                        <div class="validation_display validation_neg_margin">
                                            {{ $errors->first('password') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6 col-sm-12 col-12">
                                    <div class="field-text">
                                        <input type="password" id="password_confirmation" placeholder="Confirm Password" data-name="confirm password"
                                               class="field-input {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}"
                                               value="" name="password_confirmation" required>
                                        <div class="input-icon">
                                            <a href="javascript:;" class="showPass" tabindex="-1"><i class="fa fa-eye"
                                                                                       aria-hidden="true"></i></a>
                                        </div>
                                    </div>
                                    @if($errors->has('password_confirmation'))
                                        <div class="validation_display validation_neg_margin">
                                            {{ $errors->first('password_confirmation') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-12 col-12">
                                    <div class="field-text">
                                        <select class="field-select {{ $errors->has('country') ? 'is-invalid' : '' }}" data-name="Country"
                                                name="country" id="country" required>
                                            <option value="">Select Country</option>
                                            @foreach(\App\Helpers\Common::countries() as $country)
                                                <option value="{{$country}}" {{ old('country') ? 'selected' : ''}}>{{$country}}</option>
                                            @endforeach
                                        </select>
                                        <div class="input-icon input-icon-select">
                                            <a href="javascript:;" class="tooltip-color" tabindex="-1" data-toggle="tooltip" data-placement="top" title="While anyone from anywhere can purchase gifts from a Prezziez Wishlist, at this time only U.S. based giftees can receive gifts. We hope to expand our services soon!"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
                                        </div>
                                    </div>
                                    @if($errors->has('country'))
                                        <div class="validation_display validation_neg_margin">
                                            {{ $errors->first('country') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-6 col-sm-12 col-12">
                                    <div class="field-text">
                                        <input type="text" id="address" data-name="Address" placeholder="Address"
                                               class="field-input {{ $errors->has('address') ? 'is-invalid' : '' }}"
                                               value="{{ old('address') }}" name="address" required >
                                    </div>
                                    @if($errors->has('address'))
                                        <div class="validation_display validation_neg_margin">
                                            {{ $errors->first('address') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4 col-sm-12 col-12">
                                    <div class="field-text">
                                        <select name="state" id="state" class="field-select {{ $errors->has('state') ? 'is-invalid' : '' }}">
                                            <option value="">Select State</option>
                                            @foreach(\App\Helpers\Common::states() as $state)
                                                <option value="{{$state}}" {{ old('state') ? 'selected' : ''}}>{{$state}}</option>
                                            @endforeach
                                        </select>
                                        {{-- <input type="text" id="state" data-name="State" placeholder="State"
                                               class="field-input {{ $errors->has('state') ? 'is-invalid' : '' }}"
                                               value="" name="state" required > --}}
                                    </div>
                                    @if($errors->has('state'))
                                        <div class="validation_display validation_neg_margin">
                                            {{ $errors->first('state') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-4 col-sm-12 col-12">
                                    <div class="field-text">
                                        <select name="city" id="city" class="field-select {{ $errors->has('city') ? 'is-invalid' : '' }}">
                                            <option value="">Select City</option>
                                        </select>
                                        {{-- <input type="text" id="city" data-name="City" placeholder="City"
                                               class="field-input {{ $errors->has('city') ? 'is-invalid' : '' }}"
                                               value="" name="city" required > --}}
                                    </div>
                                    @if($errors->has('city'))
                                        <div class="validation_display validation_neg_margin">
                                            {{ $errors->first('city') }}
                                        </div>
                                    @endif
                                </div>
                                <div class="col-md-4 col-sm-12 col-12">
                                    <div class="field-text">
                                        <input type="text" id="zipcode" data-name="Zip Code" placeholder="Zipcode"
                                               class="field-input {{ $errors->has('zipcode') ? 'is-invalid' : '' }}"
                                               value="{{ old('zipcode') }}" name="zipcode" required >
                                    </div>
                                    @if($errors->has('state'))
                                        <div class="validation_display validation_neg_margin">
                                            {{ $errors->first('state') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-12">
                                <div class="field-text pb-2" style="margin-left: -10px">
                                    <p class="rem-me">
                                    <label class="main">
                                    <input type="checkbox" class="rem-me-check" name="terms_condition" value="1">
                                    <span class="geekmark"></span>
                                        By clicking you agree to the <a href="{{route('terms-condition')}}" target="_blank"> Terms and Conditions </a>
                                    </label>
                                </p>

                                </div>
                                @if($errors->has('terms_condition'))
                                    <div class="validation_display validation_neg_margin">
                                        {{ $errors->first('terms_condition') }}
                                    </div>
                                @endif
                                </div>
                            </div>
                            <input type="hidden" name="offer_gift" value="1">
                            <input type="hidden" name="fulfill_orders" value="1">
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-12">
                                    <div class="signup-page-buttn">
                                        <button type=submit class="signup-buttn">SIGN UP</button>
                                    </div>
                                </div>
                            </div>
                            <p class="member-account">Already have an account? <a href="{{route('login')}}" class="member-account-text">Login</a></p>
                        </div>
                    </form>
                </div>
                <div class="col-md-1 col-sm-1 col-12">
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script>
        const country = '{{ old("country") }}';
        const state_name = '{{ old("state") }}';
        const city_name = '{{ old("city") }}';
        $(function () {
            $('[data-toggle="tooltip"]').tooltip();
            $('.showPass').on('click', function(){
                if ($(this).children().hasClass("fa fa-eye-slash")) {
                    $(this).children().removeClass( "fa fa-eye-slash" ).addClass( "fa fa-eye" );
                }else if($(this).children().hasClass("fa fa-eye")){
                    $(this).children().removeClass( "fa fa-eye " ).addClass( "fa fa-eye-slash" );
                }
                var passInput = $(this).parent().parent().find('input');
                if(passInput.attr('type')==='password'){
                    passInput.attr('type','text');
                }else{
                    passInput.attr('type','password');
                }
            });
        })
        // Login Function
        $(document).ready(function () {

            // getCountries();

            if(state_name !== '') {
                getCities(state_name);
            }

            jQuery.validator.addMethod("noSpace", function (value, element) {
                return value.indexOf(" ") < 0 && value != "";
            }, "Space is not allowed");

            jQuery.validator.addMethod("passwordValidation", function (value, element) {
                var regexp =  /^(?=.*[A-Za-z])(?=.*[0-9])(?=.*[!@#$%.^&*])[a-zA-Z0-9!@#$%.^&*]{8,}$/;
                var re = new RegExp(regexp);
                // console.log(this.optional(element) || re.test(value));
                return this.optional(element) || re.test(value);
            }, "Must have at least 8 characters and contain a number and special character");

            jQuery.validator.addMethod("usernameValidation", function (value, element) {
                var regexp =  /^(?=.*[A-Za-z])(?=.*[0-9])(?=.*[!@#$%.^&*])[a-zA-Z0-9!@#$%.^&*]{8,}$/;
                var re = new RegExp(regexp);
                return this.optional(element) || re.test(value);
            }, "Invalid password");

            jQuery.validator.addMethod("emailValidation", function (value, element) {
                var regexp =  /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
                var re = new RegExp(regexp);
                return this.optional(element) || re.test(value);
            }, "Please enter a valid email address.");

            jQuery.validator.addMethod("nameValidation", function (value, element) {
                var regexp =  {{config('constants.VALID_NAME_VALIDATION')}};
                var re = new RegExp(regexp);
                return this.optional(element) || re.test(value);
            }, "The full name can only contain alphabets and space. e.g Taylor Smith");

            jQuery.validator.addMethod("displayNameValidation", function (value, element) {
                var regexp =  {{config('constants.VALID_NAME_VALIDATION')}};
                var re = new RegExp(regexp);
                return this.optional(element) || re.test(value);
            }, "The display name can only contain alphabets and space. e.g Taylor Smith");

            $(".registerForm").validate({
                errorClass: 'is-invalid',
                errorElement: "div",
                invalidHandler: function() {
                    $(this).find(":input.is-invalid:first").focus();
                },
                errorPlacement: function (error, element) {
                    error.insertAfter(element.closest('div'));
                    error.addClass("validation_display validation_neg_margin");
                },

                rules: {
                    password: {
                        minlength: 8,
                        noSpace: true,
                        passwordValidation: true
                    },
                    password_confirmation: {
                        minlength: 8,
                        equalTo: "#password"
                    },
                    username :{
                        noSpace: true,
                        minlength: 4,
                    },
                    email : {
                        emailValidation : true
                    },
                    name : {
                        nameValidation : true
                    },
                    state:{
                        required:1
                    },
                    city:{
                        required:1
                    }
                },
                messages: {
                    password_confirmation: {
                        minlength:"Must have at least 8 characters and contain a number and special character",
                        equalTo: "Password and Confirm Password must be same."
                },
                password:{
                    minlength:"Must have at least 8 characters and contain a number and special character"
                }
            }
            });
        });
    </script>

@endsection

