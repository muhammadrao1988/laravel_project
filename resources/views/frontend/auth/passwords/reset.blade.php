@extends('layouts.layoutfront')
@section('title', env('APP_NAME').' - Reset Password')
@section('header_display', 'display:none')
@section('alert_display', 'display:none')
@section('content')
    <!-- SIGNIN SECTION BEGIN -->
    <section class="signup-sec body-img">
        <div class="container">
            <form action="{{  url('password/reset') }}" method="post" class="reset_form">
                {{ csrf_field() }}
                <input type="hidden" name="token" value="{{ $token }}">
                <div class="row main-box-padd">
                    <div class="col-md-6 col-sm-6 col-12 balloon-img">
                    </div>
                    <div class="col-md-6 col-sm-6 col-12 white-bg">
                        <div class="signin-main-text">
                            <h4 class="log-heading">Reset Password</h4>
                            <br>
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
                            <div class="row signin-bg">
                                <div class="col-md-12 col-sm-12 col-12">
                                    <div class="field-text signin-field-border">
                                        <label class="label-text">Email</label>
                                        <input placeholder="Enter email address" type="email" id="email"
                                               class="field-input"
                                               value="{{ old('email') }}" name="email" required>
                                        <div class="sec-icon">
                                            <i class="fa fa-envelope" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                    @if($errors->has('email'))
                                        <div class="validation_display">
                                            {{ $errors->first('email') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row signin-bg">
                                <div class="col-md-12 col-sm-12 col-12">
                                    <div class="field-text signin-field-border">
                                        <label class="label-text">Password</label>
                                        <input type="password" id="password" placeholder="********"
                                               class="field-input"
                                               name="password" required>
                                        <div class="input-icon">
                                            <a href="javascript:;" class="showPass"><i class="fa fa-eye"
                                                                                          aria-hidden="true"></i></a>
                                        </div>
                                        <div class="sec-icon">
                                            <i class="fa fa-lock" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                    @if($errors->has('password'))
                                        <div class="validation_display">
                                            {{ $errors->first('password') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="row signin-bg">
                                <div class="col-md-12 col-sm-12 col-12">
                                    <div class="field-text signin-field-border">
                                        <label class="label-text"> Retype Password</label>
                                        <input data-name="retype password" type="password" id="password_confirmation" placeholder="********"
                                               class="field-input"
                                               name="password_confirmation" required>
                                        <div class="input-icon">
                                            <a href="javascript:;" class="showPass"><i class="fa fa-eye"
                                                                                          aria-hidden="true"></i></a>
                                        </div>
                                        <div class="sec-icon">
                                            <i class="fa fa-lock" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                    @if($errors->has('password_confirmation'))
                                        <div class="validation_display">
                                            {{ $errors->first('password_confirmation') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="">
                                <div class="signup-page-buttn">
                                    <button type="submit" class="signup-buttn" style="padding: 11px 120px">Reset Password</button>
                                </div>
                                <p class="rem-me">
                                    <a href="{{route('login')}}" class="pull-right forgot-text">Back to Login</a></p>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <!-- SIGNIN SECTION END -->
@endsection
@section('scripts')
    <script>
        // Login Function
        $(document).ready(function () {
            $('.showPass').on('click', function(){

                var passInputMain = $(this).parent().parent();
                var passInput = passInputMain.find('input');
                if(passInput.attr('type')==='password'){
                    passInput.attr('type','text');
                }else{
                    passInput.attr('type','password');
                }
                if ($(this).find('i').hasClass("fa fa-eye-slash")) {
                    $(this).find('i').removeClass( "fa fa-eye-slash" ).addClass( "fa fa-eye" );
                }else if($(this).find('i').hasClass("fa fa-eye")){
                    $(this).find('i').removeClass( "fa fa-eye " ).addClass( "fa fa-eye-slash" );
                }
            });

            jQuery.validator.addMethod("noSpace", function (value, element) {
                return value.indexOf(" ") < 0 && value != "";
            }, "Space is not allowed");


            jQuery.validator.addMethod("passwordValidation", function (value, element) {
                var regexp =  /^(?=.*[A-Za-z])(?=.*[0-9])(?=.*[!@#$%.^&*])[a-zA-Z0-9!@#$%.^&*]{8,}$/;
                var re = new RegExp(regexp);
                console.log(this.optional(element) || re.test(value));
                // alert("ddd");
                return this.optional(element) || re.test(value);
            }, "Must have at least 8 characters and contain a number and special character");




            $(".reset_form").validate({
                errorClass: 'no-css',
                errorElement: "div",
                errorPlacement: function (error, element) {
                    error.insertAfter(element.closest('div'));
                    error.addClass("validation_display");
                    //console.log(element);
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
                    }
                }
            });
        });
    </script>
@endsection
