@extends('layouts.layoutfront')
@section('title', env('APP_NAME').' - Forgot Password')
@section('header_display', 'display:none')
@section('alert_display', 'display:none')
@section('content')
    <!-- SIGNIN SECTION BEGIN -->
    <section class="signup-sec body-img">
        <div class="container">
            <form action="{{ route('password.email') }}" method="post" class="forgot_password_form">
                {{ csrf_field() }}
                <div class="row main-box-padd">
                    <div class="col-md-6 col-sm-6 col-12 balloon-img">
                    </div>
                    <div class="col-md-6 col-sm-6 col-12 white-bg">
                        <div class="signin-main-text">
                            <h4 class="log-heading">Forgot Password</h4>
                            <p class="log-text">Please enter your email address to reset your password</p>
                            <br>
                            @if(session('success'))
                                <div class="alert alert-success mt-5">
                                    {{ session('success') }}
                                    <a class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">x</span>
                                    </a>
                                </div>
                            @endif
                            @if(session('error'))
                                <div class="alert alert-danger mt-5">
                                    {{ session('error') }}
                                    <a class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">x</span>
                                    </a>
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
                            <div class="">
                                <br>

                                <div class="signup-page-buttn">
                                    <button type="submit" class="signup-buttn" style="padding: 11px 120px">Send Reset Link</button>
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
        $(document).ready(function () {
            $(".forgot_password_form").validate({
                errorClass: 'no-css',
                errorElement: "div",
                errorPlacement: function (error, element) {
                    error.insertAfter(element.closest('div'));
                    error.addClass("validation_display");
                    //console.log(element);
                },
            });
        });
    </script>
@endsection


