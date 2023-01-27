@extends('layouts.layoutfront')
@section('title', env('APP_NAME').' - Login')
@section('header_display', 'display:none')
@section('alert_display', 'display:none')
@section('content')
    @if($isMobile)
       <!--  <div class="mt-5"> <br><br></div> -->
    @endif
    <!-- SIGNIN SECTION BEGIN -->
    <section class="signup-sec body-img">
        <div class="container">
            <form class="login_form" action="{{ route('login') }}" method="post">
                {{ csrf_field() }}
                <div class="row main-box-padd">
                    <div class="col-md-6 col-sm-12 col-12 balloon-img">
                    </div>
                    <div class="col-md-6 col-sm-12 col-12 white-bg">
                        <div class="signin-main-text">
                            <h4 class="log-heading">Log in to Prezziez</h4>
                            <p class="log-text">Welcome back! Login with the information you entered during
                                registration.</p>
                            @if(session('success'))
                                <div class="alert alert-success mt-5">
                                    {{ session('success') }}
                                    <a href="javascript:;" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </a>
                                </div>
                            @endif
                            @if(session('error'))
                                <div class="alert alert-danger mt-5">
                                    {{ session('error') }}
                                    <a href="javascript:;" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </a>
                                </div>
                            @endif
                            @if($errors->has('general'))
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="alert alert-danger mt-3">
                                        {{ $errors->first('general') }}
                                        <a href="javascript:;" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </a>
                                    </div>
                                </div>
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
                                            <a href="javascriptvoid:(0)" id="showPass"><i id="icon" class="fa fa-eye"
                                                                            aria-hidden="true" ></i></a>
                                        </div>
                                        <div class="sec-icon">
                                            <i class="fa fa-lock lock" aria-hidden="true"></i>
                                        </div>
                                    </div>
                                    @if($errors->has('password'))
                                        <div class="validation_display">
                                            {{ $errors->first('password') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="">

                                <p class="rem-me">
                                    <label class="main">
                                    <input type="checkbox" class="rem-me-check" name="remember" value="1">
                                    <span class="geekmark"></span>
                                    Remember me
                                    <a href="{{route('password.request')}}" class="pull-right forgot-text">Forgot password?</a>
                                    </label>
                                </p>

                                <div class="signup-page-buttn">
                                    <button type="submit" class="signup-buttn">LOGIN</button>
                                </div>
                                <p class="member-account">Don’t have an account? <a href="{{route('register')}}"
                                                                                    class="member-account-text">Register</a>
                                </p>
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

        $('#showPass').on('click', function(){
                if ($("#icon").hasClass("fa fa-eye-slash")) {
                    $("#icon").removeClass( "fa fa-eye-slash" ).addClass( "fa fa-eye" );
                }else if($("#icon").hasClass("fa fa-eye")){
                    $("#icon").removeClass( "fa fa-eye " ).addClass( "fa fa-eye-slash" );
                }
            var passInput=$("#password");
            if(passInput.attr('type')==='password')
            {
                passInput.attr('type','text');
            }else{
                passInput.attr('type','password');
            }
        });

        $(".login_form").validate({
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
