@extends('layouts.layoutfront')
@section('title', env('APP_NAME').' - Account Settings')
@section('css')
    <style>
        .input-icon {
            top: 53px !important;
        }
        .remove-img .btn:focus { background-color: #c82333 !important; border-color: #bd2130 !important; color: #fff !important; }
        .account-label-input { margin-bottom: 10px}
    </style>
@endsection
@section('content')
    <!-- ACCOUNT SETTING SECTION BEGIN -->
    <section class="account-setting-sec" style="padding-top: 15px">

            <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-12 mt-0">
                    <div class="account-setting-heading">
                        <h2 class="foll-heading mb-4">Account Settings</h2>
                    </div>
                </div>
            </div>
            </div>
        <div class="info gift-text text-right mr-2" style="font-size: 14px;color: #900090;text-decoration: underline">Note: The recommended size for banner is 1920 x 350 pixels</div>

        <section class="profile-banner-sec">
                @if ($errors->has('banner'))
                    <div class="is-invlalid text-danger text-center" role="alert">
                        {{ $errors->first('banner') }}
                    </div>
                @endif
                <div class="upload__box">

                        <form action="{{route('change-profile-banner')}}" name="banner_form" id="banner_form" method="POST"
                              enctype="multipart/form-data">
                            {{csrf_field()}}
                            <input type="hidden" name="type" value="banner">
                            <div class="upload__btn-box">
                                <label class="upload__btn">
                                    <p><i class="fa fa-pencil" aria-hidden="true"></i></p>
                                    <input type="file" id="banner" name="banner" accept="image/*" class="upload__inputfile">
                                </label>
                            </div>
                        </form>

                        <form action="{{route('remove-profile-banner')}}" name="banner_form_remove" id="banner_form_remove"
                              method="POST" enctype="multipart/form-data">
                            {{csrf_field()}}
                            <input type="hidden" name="type" value="banner">
                            <div class="upload__btn-box remove_banner" style="right: 40px">
                                <label class="upload__btn">
                                    <p><i class="fa fa-trash" aria-hidden="true"></i></p>
                                </label>
                            </div>
                        </form>


                    @if($model->banner=="")
                        <div class="upload__img-wrap">
                            <img src="{{asset('image/web/profile-banner.png')}}" alt="image" class="img-fluid">
                        </div>
                    @else
                        <div class="upload__img-wrap">
                            <img src="{{ asset("storage/uploads/banner/".$model->banner)}}" alt="image"
                                 class="img-fluid">
                        </div>
                    @endif

                </div>
            </section>
        <form autocomplete="off" class="form" action="{{route('myaccountSubmit')}}" method="post" enctype="multipart/form-data" id="registrationForm">
            <input type="hidden" name="submit_profile" value="1">
            {{ csrf_field() }}
            <section class="inner-main-sec">
                <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-12">
                        <div class="avatar-upload profile-sec-img" >
                            <div class="avatar-edit">
                                <input type='file' name="profile_image" id="imageUpload" accept="image/*" />
                                <label for="imageUpload"></label>
                            </div>
                            <div class="avatar-preview">
                                <div id="imagePreview">
                                </div>
                                {{--<div id="drop-region">
                                </div>--}}
                            </div>
                        @if($model->profile_image !="")
                            <div class="remove-img">
                                <button class="btn btn-danger">Remove Image</button>
                            </div>
                            @endif
                        </div>
                        @if ($errors->has('profile_image'))
                            <div class="is-invlalid text-danger font-weight-bold text-center" role="alert">
                                {{ $errors->first('profile_image') }}
                            </div>
                        @endif
                        <div class="profile_image_error text-danger font-weight-bold text-center" style="margin-top: -30px"></div>
                    </div>
                    <div class="col-md-12 info gift-text text-center" style="font-size: 12px;color: #900090;text-decoration: underline">Note: The recommended size for profile image is 350 x 265 pixels</div>

                </div>
                </div>
            </section>
            <div class="container">
            <div class="row">

                <div class="col-md-12 col-sm-12 col-12">
                    <div class="account-label-field">
                        <label class="account-label-text">Short Description</label>
                        <input  type="text" id="short_description" data-name="Short Description"
                               class="mb-0 account-label-input {{ $errors->has('short_description') ? 'is-invalid' : '' }}"
                               value="{{ (old('short_description'))? old('short_description'): $model->short_description }}"
                               placeholder="Enter short description"
                               name="short_description">
                        <div class="help-block mb-4">This will appear on your profile page under your profile picture.</div>
                    </div>
                    @if($errors->has('name'))
                        <div class="validation_display">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-12">
                    <div class="account-label-field">
                        <label class="account-label-text">Full Name *</label>
                        <input required type="text" id="name" data-name="Full Name"
                               class="account-label-input {{ $errors->has('name') ? 'is-invalid' : '' }}"
                               value="{{ (old('name'))? old('name'): $model->name }}"
                               placeholder="Enter full name"
                               name="name">
                    </div>
                    @if($errors->has('name'))
                        <div class="validation_display">
                            {{ $errors->first('name') }}
                        </div>
                    @endif
                </div>
                <div class="col-md-6 col-sm-6 col-12">
                    <div class="account-label-field">
                        <label class="account-label-text">Display Name *</label>
                        <input type="text" id="displayName" data-name="Display Name" required
                               class="account-label-input{{ $errors->has('displayName') ? ' is-invalid' : '' }}"
                               value="{{ (old('displayName'))? old('displayName'): $model->displayName }}"
                               placeholder="Enter display name"
                               name="displayName">
                    </div>
                    @if($errors->has('displayName'))
                        <div class="validation_display">
                            {{ $errors->first('displayName') }}
                        </div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-12 mt-3">
                    <div class="account-label-field">
                        <label class="account-label-text">Email Address *</label>
                        <input disabled type="email"
                               class="account-label-input{{ $errors->has('email') ? ' is-invalid' : '' }}"
                               value="{{ (old('email'))? old('email'): $model->email}}"
                               placeholder="Enter email address"
                               >
                    </div>
                    <input type="hidden" name="email" value="{{ (old('email'))? old('email'): $model->email}}">
                    @if($errors->has('email'))
                        <div class="validation_display">
                            {{ $errors->first('email') }}
                        </div>
                    @endif
                </div>
                <div class="col-md-6 col-sm-6 col-12 mt-3">
                    <div class="account-label-field">
                        <label class="account-label-text">Username *</label>
                        <div class="input-group mb-2">
                            <div class="input-group-prepend">

                            </div>
                            <input type="text" id="username" required autocomplete="off"
                                   class="account-label-input{{ $errors->has('username') ? ' is-invalid' : '' }}"
                                   value="{{ (old('username'))? old('username'): $model->username}}"
                                   placeholder="Enter username"
                                   name="username">
                        </div>
                    </div>
                    @if($errors->has('username'))
                        <div class="validation_display">
                            {{ $errors->first('username') }}
                        </div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-12 mt-3">
                    <div class="account-label-field">
                        <label class="account-label-text">Contact Number</label>
                        <input type="text" id="contactNumber" data-name="Contact Number" autocomplete="off"
                               class="account-label-input{{ $errors->has('contactNumber') ? ' is-invalid' : '' }}"
                               value="{{ (old('contactNumber'))? old('contactNumber'): $model->contactNumber }}"
                               placeholder="Add reference phone number"
                               name="contactNumber">
                    </div>
                    @if($errors->has('contactNumber'))
                        <div class="validation_display">
                            {{ $errors->first('contactNumber') }}
                        </div>
                    @endif
                </div>

            </div>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-12 mt-3">
                    <div class="account-label-field">
                        <label class="account-label-text">Password </label>
                        <input type="password" id="password" {{$model->exists ? "" : "required"}} autocomplete="off"
                               class="account-label-input{{ $errors->has('password') ? ' is-invalid' : '' }}"
                               value=""
                               placeholder="Enter password"
                               name="password">
                        <div class="input-icon" style="right: 25px">
                            <a href="javascript:;" class="showPass"><i class="fa fa-eye"
                                                                       aria-hidden="true"></i></a>
                        </div>
                    </div>
                    @if($errors->has('password'))
                        <div class="validation_display">
                            {{ $errors->first('password') }}
                        </div>
                    @endif
                </div>
                <div class="col-md-6 col-sm-6 col-12 mt-3">
                    <div class="field-text account-label-field">
                        <label class="account-label-text">Confirm Password </label>
                        <input type="password" id="password_confirmation" {{$model->exists ? "" : "required"}}
                        class="account-label-input{{ $errors->has('password_confirmation') ? ' is-invalid' : '' }}"
                               value=""
                               placeholder="Enter confirm password"
                               data-name="Confirm Password"
                               name="password_confirmation">
                        <div class="input-icon">
                            <a href="javascript:;" class="showPass"><i class="fa fa-eye"
                                                                       aria-hidden="true"></i></a>
                        </div>
                    </div>
                    @if($errors->has('password_confirmation'))
                        <div class="validation_display">
                            {{ $errors->first('password_confirmation') }}
                        </div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-12 mt-3">
                    <div class="account-label-field">
                        <label class="account-label-text">Address *</label>
                        <input type="text" id="address" required
                               class="account-label-input{{ $errors->has('address') ? ' is-invalid' : '' }}"
                               value="{{ (old('address'))? old('address'): $model->address }}"
                               placeholder="Enter address"
                               name="address">
                    </div>
                    @if($errors->has('address'))
                        <div class="validation_display">
                            {{ $errors->first('address') }}
                        </div>
                    @endif
                </div>
                <div class="col-md-6 col-sm-6 col-12 mt-3">
                    <div class="account-label-field">
                        <label class="account-label-text">Country *</label>
                        <select id="country" name="country" required
                                class="account-label-input{{ $errors->has('country') ? ' is-invalid' : '' }} select2"
                                style="width: 100%">
                            <option value="">Select Country</option>
                            @foreach(\App\Helpers\Common::countries() as  $country)
                                <option value="{{$country}}" {{ (old('country') == $country) ? 'selected=""':'' }} {{ ($model->country == $country) ? 'selected=""':''}} >
                                    {{ $country }}</option>
                            @endForeach
                        </select>
                        @if($errors->has('country'))
                            <div class="validation_display">
                                {{ $errors->first('country') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-12 mt-3">
                    <div class="account-label-field">
                        <label class="account-label-text">State *</label>
                        <select id="state" name="state" required
                                class="account-label-input{{ $errors->has('state') ? ' is-invalid' : '' }} select2"
                                style="width: 100%">
                            <option value="">Select</option>
                            @foreach(\App\Helpers\Common::states() as $state)
                                <option value="{{$state}}" {{ strtolower(old('state',$model->state))==strtolower($state) ? 'selected' : ''}}>{{$state}}</option>
                            @endforeach
                        </select>
                    </div>
                    @if($errors->has('state'))
                        <div class="validation_display">
                            {{ $errors->first('state') }}
                        </div>
                    @endif
                </div>
                <div class="col-md-6 col-sm-6 col-12 mt-3">
                    <div class="account-label-field">
                        <label class="account-label-text">City *</label>
                        <select id="city" name="city" required
                                class="account-label-input{{ $errors->has('city') ? ' is-invalid' : '' }} select2"
                                style="width: 100%">
                            <option value="">Select</option>
                        </select>
                        <!-- <input type="text" id="city" required
                               class="account-label-input{{ $errors->has('city') ? ' is-invalid' : '' }}"
                               value="{{ (old('city'))? old('city'): $model->city }}"
                               placeholder="City"
                               name="city"> -->
                    </div>
                    @if($errors->has('city'))
                        <div class="validation_display">
                            {{ $errors->first('city') }}
                        </div>
                    @endif
                </div>
                <div class="col-md-6 col-sm-6 col-12 mt-3">
                    <div class="account-label-field">
                        <label class="account-label-text">Zip Code *</label>
                        <input type="text" id="zip" required data-name="Zip code"
                               class="account-label-input {{ $errors->has('zip') ? ' is-invalid' : '' }}"
                               value="{{ (old('zip',$model->zip)) }}"
                               placeholder="Enter zipcode"
                               name="zip">
                    </div>
                    @if($errors->has('zip'))
                        <div class="validation_display">
                            {{ $errors->first('zip') }}
                        </div>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-sm-6 col-12">
                    <div class="notify-text">
                        <h4 class="notify-set-text">Notification Settings</h4>
                        <ul>
                            @foreach(\App\Helpers\Common::notification_settings("array") as $setting_key=>$setting_value)
                                <label>
                                <li class="notify-set-list">
                                    <input name="notification_setting[]" value="{{$setting_key}}" type="checkbox" class="notify-set-field" {{in_array($setting_key,$notification_setting) ? "checked" :""}}>
                                    {{$setting_value}}
                                </li>
                                </label>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-sm-12">
                    <div class="notify-text">
                        <h4 class="notify-set-text">Email Notifications</h4>
                        <ul>
                            @foreach(\App\Helpers\Common::notification_settings("array") as $setting_key=>$setting_value)
                                <label>
                                <li class="notify-set-list">
                                    <input name="email_notification_setting[]" value="{{$setting_key}}" type="checkbox" class="notify-set-field" {{in_array($setting_key,$email_notification_setting) ? "checked" :""}}>
                                    {{$setting_value}}
                                </li>
                                </label>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            <div class="gift-offers-heading">
                <h4 class="notify-set-text">Gift Offers</h4>
                <div class="multi-button">
                    <button type="button" data-val="1" class="cut {{old('offer_gift',$model->offer_gift) == 1 ? "active" : ""}}">Receive gift offers</button>
                    <button type="button" data-val="0" class="cut {{old('offer_gift',$model->offer_gift) == 0 ? "active" : ""}}">Do not receive gift offers</button>
                    <input type="hidden" name="offer_gift" class="tab_hidden" value="{{old('offer_gift',$model->offer_gift)}}">
                </div>
            </div>
            <div class="gift-offers-heading">
                <h4 class="notify-set-text">Fulfill orders for items that are outpriced or out of stock using <a href="{{route('faq','#headingNineteen')}}" class="highlight-merchants"><u>comparable merchants.</u></a></h4>
                <div class="multi-button">
                    <button type="button" data-val="1" class="cut {{old('fulfill_orders',$model->fulfill_orders) == 1 ? "active" : ""}}">Allow</button>
                    <button type="button" data-val="0" class="cut {{old('fulfill_orders',$model->fulfill_orders) == 0 ? "active" : ""}}">Do Not Allow</button>
                    <input type="hidden" name="fulfill_orders" class="tab_hidden" value="{{old('fulfill_orders',$model->fulfill_orders)}}">
                </div>
            </div>
            <div class="gift-offers-heading">
                <h4 class="notify-set-text">Privacy Settings</h4>
                <ul>
                    <li class="notify-set-list">
                        <input name="privacy_setting" value="1" type="checkbox" {{ (old('privacy_setting',$model->privacy_setting)==1) ? "checked"  : "" }} class="notify-set-field">
                        Private Account
                    </li>
                    <li class="notify-set-list">
                        This will make your wishlists private and only visible to those who follow you.
                    </li>
                </ul>
            </div>
            <div class="gift-offers-heading">
                <h4 class="notify-set-text">Account Settings</h4>
                <ul>
                    <li class="notify-set-list">
                        <input name="active" value="-1" id="deactive_account" type="checkbox" class="notify-set-field">
                        Deactivate Account
                    </li>
                </ul>
            </div>
            <div class="update-account-main">
                <button type="button" class="checkout-buttn" id="account_update_btn">Update Account</button>
            </div>
        </div>
        <input type="hidden" name="remove_img" value="0" id="remove-img">
        </form>
    </section>
    <!-- ACCOUNT SETTING SECTION END -->
    <div class="modal modal1 fade" id="imageChangeModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-slideout" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="image_selected_type" value="">
                    {{--<h5 class="modal-title add-prezziez-heading align-right" id="exampleModalLabel">Remove from Cart</h5>--}}
                    <p class="add-prezziez-text">Do you want to apply?</p>
                    <br>
                    <button type="button" style="margin: 0 auto" class="add-next apply_image_confirm">Confirm</button>
                    <br>
                    <a href="javascript:;"  data-dismiss="modal" class="mt-2 text-center close_image_change" style="display: block">Cancel</a>
                </div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')

    <script>
        var remove_img=0;
        function readURL(input) {
            if (input.files && input.files[0]) {

                var reader = new FileReader();
                reader.onload = function(e) {
                    var fileName = input.files[0].name;
                    if(isImage(fileName)){
                        $(".profile_image_error").html("");
                        var image = new Image();
                        image.src = e.target.result;
                        image.onload = function () {
                            var height = this.height;
                            var width = this.width;
                           /* if (height < parseInt("{{config('constants.PROFILE_IMG_HEIGHT')}}") || width < parseInt("{{config('constants.PROFILE_IMG_WIDTH')}}")) {

                                //show width and height to user
                                $("#imageUpload").val("");
                                $(".profile_image_error").html("{{config('constants.PROFILE_IMG_ERR_MSG')}}");
                                return false;
                            }else*/
                            if(input.files[0].size> 2097152) {
                                $(".profile_image_error").html("The profile image size should not be greater than 2 mb.");
                                $("#imageUpload").val("");
                                return false;
                            }else{
                                $('#imagePreview').css('background-image', 'url('+e.target.result +')');
                                $('#imagePreview').hide();
                                $('#imagePreview').fadeIn(650);
                            }
                        };

                    }
                    else{
                        $("#imageUpload").val("");
                        $(".profile_image_error").html("The profile image must be a file of type: jpeg, png, jpg, or gif");
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        function getExtension(filename) {
            var parts = filename.split('.');
            return parts[parts.length - 1];
        }
        function isImage(fileName) {
            var ext = getExtension(fileName);
            switch (ext.toLowerCase()) {
                case 'jpg':
                case 'jpeg':
                case 'png':
                case 'gif':
                return true;
            }
            return false;
        }
    </script>
    <script>
        var country = "<?php echo $model->country; ?>";
        var city_name = "<?php echo $model->city; ?>";
        var state_name = "<?php echo $model->state; ?>";
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

            if (country != ''){
                $('#country').trigger('change')
            }

            if (state_name != ''){
                $('#state').trigger('change')
            }

            getCities( "<?php echo $model->state; ?>" );
            var image_url = "{{$model->profile_image}}";

            if(image_url!=""){
                 image_url = "{{asset("storage/uploads/profile_picture/".$model->profile_image)}}";
                $('#imagePreview').css('background-image', 'url('+image_url +')');
                $('#imagePreview').fadeIn(650);
            }

            $(".cut").click(function () {
                $(this).parent().find('.cut').removeClass('active');
                $(this).addClass('active');
                $(this).parent().find('.tab_hidden').val($(this).attr('data-val'));
            });

            jQuery.validator.addMethod("noSpace", function (value, element) {
                if(value!="") {
                    return value.indexOf(" ") < 0 && value != "";
                }else{
                    return true;
                }
            }, "Space is not allowed");


            jQuery.validator.addMethod("passwordValidation", function (value, element) {
                var regexp =  /^(?=.*[A-Za-z])(?=.*[0-9])(?=.*[!@.#$%^&*])[a-zA-Z0-9!@.#$%^&*]{8,}$/;
                var re = new RegExp(regexp);
                return this.optional(element) || re.test(value);
            }, "Must have at least 8 characters and contain a number and special character");

            jQuery.validator.addMethod("usernameValidation", function (value, element) {
                var regexp =  /^(?=.*[A-Za-z])(?=.*[0-9])(?=.*[!@#$%^&*])[a-zA-Z0-9!@#$%^&*]{8,}$/;
                var re = new RegExp(regexp);
                return this.optional(element) || re.test(value);
            }, "Invalid username");

            jQuery.validator.addMethod("nameValidation", function (value, element) {
                var regexp =  {{config('constants.VALID_NAME_VALIDATION')}};
                var re = new RegExp(regexp);
                return this.optional(element) || re.test(value);
            }, "The full name can only contain alphabets and space. e.g Taylor Smith");

            jQuery.validator.addMethod("displayNameValidation", function (value, element) {
                var regexp = {{config('constants.VALID_NAME_VALIDATION')}};
                var re = new RegExp(regexp);
                return this.optional(element) || re.test(value);
            }, "The display name can only contain alphabets and space. e.g Taylor Smith");

            jQuery.validator.addMethod("contactNumberValidation", function (value, element) {
                var regexp =  /^[0-9\-\(\)\s]+$/;
                var re = new RegExp(regexp);
                return this.optional(element) || re.test(value);
            }, "Invalid contact number");
            jQuery.validator.addMethod("zipValidation", function (value, element) {
                var regexp =   /^[0-9]{5}(?:-[0-9]{4})?$/;
                var re = new RegExp(regexp);
                return this.optional(element) || re.test(value);
            }, "Invalid zipcode");

            $("#registrationForm").validate({
                invalidHandler: function() {
                    setTimeout(function () {
                        $("#registrationForm").find(".is-invalid:first").focus();
                    },500);

                },
                errorClass: 'is-invalid',
                errorElement: "div",
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
                    name : {
                        nameValidation : true
                    },
                    contactNumber :{
                        contactNumberValidation : true
                    },
                    zip :{
                        zipValidation : true
                    }

                },
                messages: {
                    password_confirmation: {
                        minlength:"Must have at least 8 characters and contain a number and special character",
                        equalTo: "Password and Confirm Password must be same."
                },
                password:{
                    minlength:"Must have at least 8 characters and contain a number and special character"
                },
            }
            });

            $("#imageUpload").change(function() {
                $('#remove-img').val('0');
                readURL(this);
            });
        });
        $( "#account_update_btn" ).click(function( event ) {
            //event.preventDefault();
            if($("#registrationForm").valid()) {
                var deactive_account = $('#deactive_account')[0].checked;
                if (deactive_account) {
                    if(confirm("Do you really want to Deactive your account?")){
                        $("#account_update_btn").attr('disabled', true);
                        $("#account_update_btn").text('Please wait');
                        $( "#registrationForm" ).submit();

                    }else{
                        $("#account_update_btn").attr('disabled', false);
                        $("#account_update_btn").text('UPDATE ACCOUNT');
                    }
                }else{
                    $("#account_update_btn").attr('disabled', true);
                    $("#account_update_btn").text('Please wait');
                    $( "#registrationForm" ).submit();
                }
            }
        });
        $('.remove-img').click(function(e){
            e.preventDefault();
            $('#imagePreview').css('background-image', 'url({{asset('image/web/placeholder.png')}})');
            $('#imagePreview').fadeIn(650);
            //$(this).find('button').attr('disabled',true);
            $('#remove-img').val('1');

          $("#profile_image").val('');
        });
//banner

        $(".remove_banner").click(function () {
            if (confirm("Do you really want to remove banner?")) {
                $("#banner_form_remove").submit();
            }
        });

        $(".upload__inputfile,.upload__inputfile_profile").on('change', function (e) {
            var imgWrap = "";
            var imgArray = [];
            var className = $(this).attr('class');
            if (className == "upload__inputfile") {
                imgWrap = $(this).closest('.upload__box').find('.upload__img-wrap');
                var type = "banner";
            } else {
                imgWrap = $(this).closest('.profile-sec-img').find('.profile-img-preview');
                var type = "profile";
            }
            var maxLength = $(this).attr('data-max_length');
            var isSubmit = true;

            var files = e.target.files;
            var filesArr = Array.prototype.slice.call(files);
            var iterator = 0;
            filesArr.forEach(function (f, index) {

                if (!f.type.match('image.*')) {
                    toastr.error("The image must be a file of type: jpeg, png, jpg, or gif", "Error");
                    return;
                }

                if (imgArray.length > maxLength) {
                    toastr.error("The image size should not be greater than 2 mb.", "Error");
                    return false
                } else {
                    var len = 0;
                    for (var i = 0; i < imgArray.length; i++) {
                        if (imgArray[i] !== undefined) {
                            len++;
                        }
                    }
                    if (len > maxLength) {
                        return false;
                    } else {
                        imgArray.push(f);

                        var reader = new FileReader();
                        reader.onload = function (e) {
                            var image = new Image();
                            image.src = e.target.result;
                            image.onload = function () {
                                var height = this.height;
                                var width = this.width;
                                if (type == "banner") {
                                    var html = "<div class='upload__img-box'><div style='background-image: url(" + e.target.result + ")' data-number='" + $(".upload__img-close").length + "' data-file='" + f.name + "' class='img-bg'><div class='upload__img-close'></div></div></div>";
                                    imgWrap.append(html);
                                    iterator++;
                                    /*if (height != "{{config('constants.BANNER_HEIGHT')}}" || width < parseInt("{{config('constants.BANNER_WIDTH')}}")) {
                                            isSubmit = false;
                                            //show width and height to user

                                            return false;
                                        } else {
                                            var html = "<div class='upload__img-box'><div style='background-image: url(" + e.target.result + ")' data-number='" + $(".upload__img-close").length + "' data-file='" + f.name + "' class='img-bg'><div class='upload__img-close'></div></div></div>";
                                            imgWrap.append(html);
                                            iterator++;
                                        }*/
                                } else {
                                    $(".profile-sec-img .profile-img-preview").prop("src", e.target.result);
                                    iterator++;
                                    /*if (height < parseInt("{{config('constants.PROFILE_IMG_HEIGHT')}}") || width < parseInt("{{config('constants.PROFILE_IMG_WIDTH')}}")) {
                                            isSubmit = false;
                                            //show width and height to user
                                            return false;
                                        } else {
                                            $(".profile-sec-img .profile-img-preview").prop("src", e.target.result);
                                            iterator++;
                                        }*/
                                }
                            };

                        }
                        reader.readAsDataURL(f);

                        setTimeout(function () {
                            if (isSubmit) {
                                if (type == "banner") {
                                    $("#imageChangeModal #image_selected_type").val("banner_form");
                                    $("#imageChangeModal").modal("show");
                                } else {
                                    $("#imageChangeModal #image_selected_type").val("profile_form");
                                    $("#imageChangeModal").modal("show");
                                }
                            }
                        }, 500)


                    }
                }
            });
        });
        $(".apply_image_confirm").click(function () {
            var form_id = $("#image_selected_type").val();
            $("#"+form_id).submit();
        });
        $(".close_image_change").click(function () {
            location.reload();
        })
    </script>
@endsection
