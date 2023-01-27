@extends('layouts.layoutfront')
@section('css')
    <style>
        .slick-slide img {
            display: block;
            height: 235px;
            width: 100%;
        }

        .profile-box {
            left: 56%;
            top: -35%;
        }
        input[type="date"]:not(:valid)::-webkit-datetime-edit {
            color: grey;
        }
        input[type="date"]::-webkit-calendar-picker-indicator {
            background: transparent;
            bottom: 0;
            color: transparent;
            cursor: pointer;
            height: auto;
            left: 0;
            position: absolute;
            right: 0;
            top: 0;
            width: auto;
        }

        .center-the-heading {
            justify-content: space-around !important;
        }
    </style>
@endsection
@section('content')

    <!-- PROFILE BANNER SECTION BEGIN -->
    <section class="profile-banner-sec">
        @if ($errors->has('banner'))
            <div class="is-invlalid text-danger text-center" role="alert">
                {{ $errors->first('banner') }}
            </div>
        @endif
        <div class="upload__box">
            @if($profile_detail->banner=="")
                <div class="upload__img-wrap">
                    <img src="{{asset('image/web/profile-banner.png')}}" alt="image" class="img-fluid">
                </div>
            @else
                <div class="upload__img-wrap">
                    <img src="{{ asset("storage/uploads/banner/".$profile_detail->banner)}}" alt="image"
                         class="img-fluid">
                </div>
            @endif
        </div>
    </section>
    <!-- PROFILE BANNER SECTION END -->
    <!-- INNER SECTION BEGIN -->
    <section class="inner-main-sec">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-12">
                    <div class="profile-sec-img">

                        @if($profile_detail->profile_image!="")
                            @php($img_url = asset("storage/uploads/profile_picture/".$profile_detail->profile_image))
                        @else
                            @php($img_url = asset("image/web/placeholder.png"))

                        @endif
                        {{--@if($same_profile)
                            <form action="{{route('change-profile-banner')}}" id="profile_form" method="POST"
                                  enctype="multipart/form-data">
                                {{csrf_field()}}
                                <input type="hidden" name="type" value="profile_img">
                                <div class="upload__btn-box profile-box">
                                    <label class="upload__btn">
                                        <p><i class="fa fa-pencil" aria-hidden="true"></i></p>
                                        <input accept="image/*" type="file" name="profile_img" class="upload__inputfile_profile"
                                               style="width: 0">
                                    </label>
                                </div>
                            </form>
                            @if($profile_detail->profile_image!="")
                                <form action="{{route('remove-profile-image')}}" id="profile_form_remove" method="POST"
                                      enctype="multipart/form-data">
                                    {{csrf_field()}}
                                    <input type="hidden" name="type" value="profile">
                                    <div class="upload__btn-box profile-box remove_profile" style="top: -17%">
                                        <label class="upload__btn">
                                            <p><i class="fa fa-trash" aria-hidden="true"></i></p>
                                        </label>
                                    </div>
                                </form>
                            @endif
                        @endif--}}
                        <img src="{{$img_url}}" class="img-fluid profile-img-preview"
                             alt="avatar">
                            {{--@if($same_profile)
                                <div class="info gift-text mt-2" style="font-size: 14px;color: #900090;text-decoration: underline">Note: The recommended size for profile image is 350 x 265 pixels</div>
                            @endif--}}
                        <h4 class="prof-heading">{{$profile_detail->displayName}}
                            @if($same_profile)
                                <br><a class="knot-text knot-yellow" href="{{route('myaccount')}}?show=1"> Edit Profile </a>
                            @endif
                        </h4>
                        <p class="prof-text">{{$profile_detail->short_description}}</p>
                        @if($same_profile)
                            <ul class="share-icon mt-4">
                                <li class="share-icon-list">
                                    <div class="dropdown show">
                                        <a href="javascript:;" class="btn btn-secondary dropdown-toggle share_dropdown "
                                           data-toggle-second="tooltip" data-placement="top"
                                           title="Share your profile with" href="#" role="button" id="dropdownMenuLink"
                                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-share-alt" aria-hidden="true"></i>
                                        </a>

                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                            <a class="dropdown-item"
                                               href="https://www.facebook.com/sharer/sharer.php?u={{route('profileUrl',$profile_detail->username)}}"
                                               target="_blank"><i class="fa fa-facebook-official fac-col" aria-hidden="true"></i> Facebook</a>
                                            <a class="dropdown-item" target="_blank"
                                               href="https://twitter.com/share?url={{route('profileUrl',$profile_detail->username)}}"><i class="fa fa-twitter-square twit-col" aria-hidden="true"></i> Twitter </a>
                                            <a class="dropdown-item"
                                               href="mailto:?body={{route('profileUrl',$profile_detail->username)}}&subject=Profile Link"><i class="fa fa-envelope-square email-col" aria-hidden="true"></i> Email </a>
                                        </div>
                                    </div>
                                    {{--<a href="javascriptvoid:(0)" class="share-icon-file"><i
                                                class="fa fa-share-alt" aria-hidden="true"></i></a>--}}
                                </li>
                                <li class="share-icon-list copy_clip"
                                    data-text="{{route('profileUrl',$profile_detail->username)}}"><a href="javascript:;"
                                                                                                     data-toggle="tooltip"
                                                                                                     data-placement="top"
                                                                                                     title="Copy profile URL"
                                                                                                     class="share-icon-file"><i
                                                class="fa fa-file" aria-hidden="true"></i></a></li>
                            </ul>
                        @elseif(!empty($auth_user->id))
                            <ul class="following-heading">
                                <li class="following-inner">
                                    @if($follower_detail)
                                        @if($follower_detail->following_status=="Accepted")
                                            <a href="javascript:;" data-toggle="modal" data-target="#unFollowModal"
                                               class="following-text following-text-color requested"
                                               data-status="Accepted">Following</a>
                                        @elseif($follower_detail->following_status=="Pending")
                                            <a href="javascript:;" data-target="#cancelModal" data-toggle="modal"
                                               class="following-text following-text-color requested"
                                               data-status="Pending">Pending</a>
                                            {{--<p>{{$profile_detail->displayName}} account is private.
                                                We've sent your follow request for approval.</p>--}}
                                        @else
                                            <a href="javascript:;" class="following-text following-text-color requested"
                                               data-status="Pending">Rejected</a>
                                            <p>{{$profile_detail->displayName}} has been rejected your follow
                                                request.</p>

                                        @endif
                                    @else
                                        <a href="javascriptvoid:(0)" data-toggle="modal" data-target="#followModal"
                                           class="following-text following-text-color follow_profile">Follow</a>
                                    @endif
                                </li>
                                @if($profile_detail->offer_gift==1)
                                    <li class="following-inner"><span class="line-color">|</span></li>
                                    <li class="following-inner"><a href="javascript:;" data-toggle="modal"
                                                                   data-target="#ModalSlide"
                                                                   class="following-text offer-gift">Offer a Gift</a>
                                    </li>

                                @endif
                                @if($respond)
                                    <li class="following-inner"><span class="line-color">|</span></li>
                                    <li class="following-inner">
                                        <a href="javascriptvoid:(0)" data-profile-url="{{$profile_detail->username}}"
                                           data-operation-type="acceptRejectRequest"
                                           data-dynamic-name="Accept {{$profile_detail->displayName}} follow request?"
                                           data-dynamic-img="{{$img_url}}"
                                           class="following-text operation_modal following-text-color">
                                            Respond to the request
                                        </a>
                                    </li>
                                @endif
                            </ul>
                        @else
                            <ul class="following-heading">
                                <li class="following-inner login_follow"><a href="javascript:;"
                                                                            class="following-text following-text-color">Follow</a>
                                </li>
                                @if($profile_detail->offer_gift==1)
                                    <li class="following-inner"><span class="line-color">|</span></li>
                                    <li class="following-inner"><a href="javascript:;" data-toggle="modal"
                                                                   data-target="#ModalSlide"
                                                                   class="following-text offer-gift">Offer a Gift</a>
                                    </li>
                                @endif
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
            <!-- Nav tabs -->

            @if($same_profile)
                <ul class="nav nav-tabs" role="tablist">

                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#home" role="tab">My Wishlists</a>
                    </li>

                    <li class="nav-item">
                        <span class="base-color">|</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="profile_gift_section" data-toggle="tab" href="#profile" role="tab">Gifted
                            Items</a>
                    </li>
                    <li class="nav-item">
                        <span class="base-color">|</span>
                    </li>
                    <li class="nav-item following-inner">
                        <a href="{{route('following')}}"
                           class="nav-link following-text following-text-color">Following</a>
                    </li>
                </ul>
            @endif
        <!-- Tab panes -->
            @if($show_wishlist)
                <div class="tab-content">
                    <div class="tab-pane active" id="home" role="tabpanel">
                        <div class="row wishlist_listing">
                            @if($same_profile)
                                <div class="col-md-4 col-sm-4 col-12 blue-boxes-padd">
                                    <div class="gift-card-box">
                                        <div class="blue-img">
                                            <img src="{{asset('image/web/bl1.png')}}" alt="image"
                                                 class="img-fluid">
                                            <div class="blue-img-icon">
                                                <a href="javascript:;" class="blue-img-text wishlist_main_icon" data-toggle="modal"
                                                   data-target="#wish_list_add_edit"><i class="fa fa-plus"
                                                                                        aria-hidden="true"></i></a>
                                            </div>
                                        </div>
                                        <h4 class="gift-card-heading center-the-heading">Create Wishlist</h4>
                                    </div>
                                </div>
                            @endif
                            @include('frontend.wishlist.wishlist_pagination')
                        </div>
                        @if($show_wishlist && $last_page > 1)
                            <button style="width: 100%; cursor: pointer"
                                    class="pt-3 pb-3 front-buttn text-center cursor-pointer mb-4"
                                    id="show-more">See More
                            </button>
                        @endif
                    </div>
                    <div class="tab-pane" id="profile" role="tabpanel">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-12 profile_gifted_offers">

                            </div>
                            <div class="col-12 loading_offer text-center" style="display: none">
                                <img width="100" height="100" src="{{asset('image/loader/Spin-1s-200px.gif')}}">
                            </div>
                            <input type="hidden" id="last_page_offer" value="0">
                            <button class="col-12 pt-3 pb-3 front-buttn text-center cursor-pointer mb-4"
                                    style="display: none"
                                    id="show-more-offer">See More
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </section>
    <!-- INNER SECTION END -->

    <!-- Modal -->
    <!-- Modal -->
    <!-- MODAL Wishlist add -->
    @include('frontend.wishlist.wishlist_add_edit_modal')
    <!-- MODAL  END -->
    <!-- MODAL Wishlist title date -->
    @include('frontend.wishlist.wishlist_title_modal')
    <!-- MODAL END -->
    <!-- Modal Follow-->
    <div class="modal popup-modal fade" id="followModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="modal-body-content">
                        @if($profile_detail->profile_image!="")
                            <img src="{{ asset("storage/uploads/profile_picture/".$profile_detail->profile_image)}}"
                                 class="img-fluid" alt="">
                        @else
                            <img src="{{asset('image/web/placeholder.png')}}" class="img-fluid"
                                 alt="avatar">
                        @endif

                        <h4 class="unfol-joe">Follow {{$profile_detail->displayName}}?</h4>
                        <div class="modal-unfollow-buttn">
                            <button type="button" class="unfol-joe-col confirm_follow"
                                    onclick="followOperation('follow_request','followModal');">Confirm
                            </button>
                            <button type="button" class="unfol-joe-trans" data-dismiss="modal">CANCEL</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL END -->
    <!-- Modal Un-Follow-->
    <div class="modal popup-modal fade" id="unFollowModal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="modal-body-content">
                        @if($profile_detail->profile_image!="")
                            <img src="{{ asset("storage/uploads/profile_picture/".$profile_detail->profile_image)}}"
                                 class="img-fluid" alt="">
                        @else
                            <img src="{{asset('image/web/placeholder.png')}}" class="img-fluid"
                                 alt="avatar">
                        @endif

                        <h4 class="unfol-joe">Unfollow {{$profile_detail->displayName}}?</h4>
                        <div class="modal-unfollow-buttn">
                            <button type="button" class="unfol-joe-col confirm_unfollow"
                                    onclick="followOperation('unfollow_following_request','unFollowModal');">Confirm
                            </button>
                            <button type="button" class="unfol-joe-trans" data-dismiss="modal">CANCEL</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL END -->
    <!-- Modal Cancel Pending Request-->
    <div class="modal popup-modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="modal-body-content">
                        @if($profile_detail->profile_image!="")
                            <img src="{{ asset("storage/uploads/profile_picture/".$profile_detail->profile_image)}}"
                                 class="img-fluid" alt="">
                        @else
                            <img src="{{asset('image/web/placeholder.png')}}" class="img-fluid"
                                 alt="avatar">
                        @endif

                        <h4 class="unfol-joe">Cancel Request?</h4>
                        <div class="modal-unfollow-buttn">
                            <button type="button" class="unfol-joe-col confirm_unfollow"
                                    onclick="followOperation('cancel_request','cancelModal');">Confirm
                            </button>
                            <button type="button" class="unfol-joe-trans" data-dismiss="modal">CANCEL</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- MODAL END -->
    @if($respond)
        @include('frontend.follows.follower_accept_reject_modal')
    @endif
    <!-- offer a gift modals-->
    @if($profile_detail->offer_gift==1 && !$same_profile)
        <!-- MODAL 1 Add Url-->
        @include('frontend.wishlist.wishlist_add_url_modal')
        <!--  Wishlist Item BEGIN -->
        @include('frontend.wishlist.wishlist_item_add_edit_modal')
        <!-- MODAL 2 END -->
    @endif

    @if($same_profile)
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
    @endif
    <!-- offer a gift modals end-->

@endsection
@section('scripts')
    <script src="{{ asset('js/helper.js')}}"></script>
    <script>
        @if($show_wishlist)
        function loadMoreData(page, last_page) {
            $.ajax({
                url: '{{route('profileUrl',$profile_detail->username)}}?page=' + page,
                type: 'get',
                beforeSend: function () {
                    $("#show-more").attr("disabled", true);
                    $("#show-more").text("Loading..");
                }
            })
                .done(function (data) {

                    if (data != "") {
                        $(".wishlist_listing").append(data);
                        setTimeout(function () {
                            $("body .wishlist_listing .responsive").not('.slick-initialized').slick(slickSlideSetting())

                        }, 500);
                    }
                    $("#show-more").removeAttr("disabled");
                    $("#show-more").text("See More");
                    if (parseInt(last_page) <= parseInt(page)) {
                        $("#show-more").hide();
                    } else {
                        $("#show-more").show();
                    }
                })
                // Call back function
                .fail(function (jqXHR, ajaxOptions, thrownError) {
                    $("#show-more").removeAttr("disabled");
                    $("#show-more").text("See More");
                    alert("Server not responding.....");
                });

        }

        $(document).ready(function () {
            //function for Scroll Event
            var page = 1;
            var last_page = "{{$last_page}}";
            last_page = parseInt(last_page);
            $('body').on('click', '#show-more', function () {
                page++; //page number increment
                loadMoreData(page, last_page);
            });

        })
        @endif
        @if($respond)
        $("body").on("click", ".operation_modal", function () {
            var operation_type = $(this).attr('data-operation-type');
            var profile_url = $(this).attr('data-profile-url');
            var dynamic_name = $(this).attr('data-dynamic-name');
            var dynamic_img = $(this).attr('data-dynamic-img');

            $("#acceptRejectModal .dynamic_img").attr("src", dynamic_img);
            $("#acceptRejectModal .dynamic_name").html(dynamic_name);
            $("#acceptRejectModal .profile_url").val(profile_url);
            $("#acceptRejectModal .operation_type").val("");
            $("#acceptRejectModal").modal("show");

        });
        $("body").on("click", ".acceptRejectBtn", function () {
            $("#acceptRejectModal .operation_type").val($(this).attr('data-value'));
            setTimeout(function () {
                followOperation($("#acceptRejectModal .operation_type").val(), $("#acceptRejectModal .profile_url").val(), 'acceptRejectModal');

            }, 500);

        });

        function followOperation(operaionType, profileUrl, modal_id) {
            var operation_type = operaionType;
            var profileUrl = profileUrl;
            var redirect_url = "{{route('profileUrl',$profile_detail->username)}}";
            var data = {
                'operation_type': operaionType,
                "username": profileUrl,
                "_token": "{{csrf_token()}}",
                "redirect_url": redirect_url
            };
            $.easyAjax({
                url: "{{ route('follow_operation') }}",
                type: "POST",
                data: data,
                container: "#" + modal_id + " .modal-body",
                messagePosition: "toastr",
            });


        }

        @endif

        function followOperation(operaionType, modal_id) {
            var operation_type = operaionType;
            var profileUrl = "{{$profile_detail->username}}";
            var data = {'operation_type': operaionType, "username": profileUrl, "_token": "{{csrf_token()}}"};
            $.easyAjax({
                url: "{{ route('follow_operation') }}",
                type: "POST",
                data: data,
                container: "#" + modal_id + " .modal-body",
                messagePosition: "toastr",
            });


        }

        $(document).ready(function () {

            $('.wishlist_main_icon').click(function () {
                $("#wish_list_title_modal #wishlist_form").trigger("reset");
                $('input[name=gift_type]:checkbox').prop('checked',false);
            });

            $(".login_follow").click(function () {
                toastr.error("Please first login or sign up to follow this profile.")
            });
            //wishlist operation
            $('.next_wishlist').click(function () {

                var checkedNum = $('input[name="gift_type"]:checked');
                $(".error_wishlist_type").text("");
                if (!checkedNum.length) {
                    // User didn't check any checkboxes
                    $(".error_wishlist_type").text("Please select one type");
                    return false;
                } else {

                    $("#wish_list_title_modal #gift_type").val(checkedNum.val());
                    if (checkedNum.val() == "general") {
                        $(".date_type").hide();
                    } else {
                        $(".date_type").show();
                    }

                    $('#wish_list_add_edit').modal('hide');
                    $('#wish_list_title_modal').modal('show');

                }

            });
            $('.back-icon-link').click(function () {
                $('#wish_list_title_modal').modal('hide');
                $('#wish_list_add_edit').modal('show');
            });
            $('form#wishlist_form input').keydown(function (e) {
                if (e.keyCode == 13) {
                    e.preventDefault();
                    $(".submit_wishlist").trigger("click");
                    return false;
                }
            });
            $(".submit_wishlist").click(function (e) {

                var data = $('#wishlist_form').serialize();
                $(".submit_wishlist").attr('disabled', true);
                $(".submit_wishlist").text("Please wait..");
                $.ajax({
                    url: "{{route('add.wishlist')}}",
                    dataType: "json",
                    type: "POST",
                    data: data
                }).then(function (data) {

                    location.href = data.url;

                }).fail(function (error) {
                    $(".submit_wishlist").attr('disabled', false);
                    $(".submit_wishlist").text("CREATE LIST");
                    if (error.responseJSON.hasOwnProperty('errors')) {
                        var error_msg = "";

                        for (var prop in error.responseJSON.errors) {

                            $(error.responseJSON.errors[prop]).each(function (val, msg) {

                                error_msg += '' + msg + "<br>";
                                toastr.error(msg, "Error");
                            });
                        }

                    } else {
                        toastr.error(error.responseJSON.message, "Error");
                    }

                });

            });
            $("input[name='gift_type']:checkbox").click(function () {
                var group = "input:checkbox[name='" + $(this).attr("name") + "']";
                $(group).prop("checked", false);
                $(this).prop("checked", true);
            });

        })
    </script>
    <script>
        function slickSlideSetting() {
            return {
                dots: false,
                infinite: true,
                arrows: true,
                nextArrow: "<img src='{{asset('image/web/right-slide.png')}}' class='a-right-arrow'>",
                prevArrow: "<img src='{{asset('image/web/left-slide.png')}}' class='a-left-arrow'>",
                autoplay: true,
                speed: 300,
                slidesToShow: 1,
                slidesToScroll: 1,
                responsive: [
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                            infinite: true,
                            dots: true
                        }
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1
                        }
                    }
                ]
            }
        }

        function slickSlide() {
            $('.wishlist_listing .responsive').slick(slickSlideSetting());
        }
    </script>
    <script>
        jQuery(document).ready(function () {
            $(".remove_profile").click(function () {
                if (confirm("Do you really want to remove profile image?")) {
                    $("#profile_form_remove").submit();
                }
            });
            $('[data-toggle-second="tooltip"]').tooltip();
            $(".close_image_change").click(function () {
                location.reload();
            })
            slickSlide();
            $('[data-toggle="tooltip"]').tooltip();
            $(".copy_clip").click(function () {

                var copyText = $(this).attr('data-text');

                /* Copy the text inside the text field */
                navigator.clipboard.writeText(copyText);

                /* Alert the copied text */
                toastr.success("Profile url copied successfully.")
            })
        });

        function ImgUpload() {


            $('body').on('click', ".upload__img-close", function (e) {
                var file = $(this).parent().data("file");
                for (var i = 0; i < imgArray.length; i++) {
                    if (imgArray[i].name === file) {
                        imgArray.splice(i, 1);
                        break;
                    }
                }
                $(this).parent().parent().remove();
            });
        }
    </script>
    @if($same_profile)
        <script>
            @if(request()->get('show_wish')==1)
                $(".wishlist_main_icon").trigger("click");
            @endif
            $("body").on("click", ".delete_wishlist", function (e) {
                var id = $(this).attr('data-id');
                var data = {id:id,"_token":"{{csrf_token()}}"};
               if(confirm("Do you really want to delete?")) {
                   $.ajax({
                       url: "{{route('remove.wishlist')}}",
                       dataType: "json",
                       type: "POST",
                       data: data
                   }).then(function (data) {

                       location.reload();

                   }).fail(function (error) {
                       $(".submit_wishlist").attr('disabled', false);
                       $(".submit_wishlist").text("CREATE LIST");
                       if (error.responseJSON.hasOwnProperty('errors')) {
                           var error_msg = "";

                           for (var prop in error.responseJSON.errors) {

                               $(error.responseJSON.errors[prop]).each(function (val, msg) {

                                   error_msg += '' + msg + "<br>";
                                   toastr.error(msg, "Error");
                               });
                           }

                       } else {
                           toastr.error(error.responseJSON.message, "Error");
                       }
                   });
               }

            });
        </script>
    @endif

    <!-- offer a gift modals-->
    @if($profile_detail->offer_gift==1 && !$same_profile)
        <script>
            function reset_wishlist_item_modal() {

                $("#wishlist_item_add_edit_modal input:checkbox[name='accept_donation']").prop('checked', false);
                $("#wishlist_item_add_edit_modal input:checkbox[name='digital_purchase']").prop('checked', false);
                $("#wishlist_item_add_edit_modal #id").val("");
                $("#wishlist_item_add_edit_modal #file_input").val("");
                $("#wishlist_item_add_edit_modal #merchant").val("");
                $("#wishlist_item_add_edit_modal #gift_details").val("");
                $("#wishlist_item_add_edit_modal #quantity").val("");
                $("#wishlist_item_add_edit_modal #price").val("");
                $("#wishlist_item_add_edit_modal #gift_name").val("");
                $("#wishlist_item_add_edit_modal #shipping_cost").val("");
                $("#wishlist_item_add_edit_modal #expedited_shipping_fee").val("");
                $("#wishlist_item_add_edit_modal #shipping_cost").removeAttr("disabled");
                $("#wishlist_item_add_edit_modal #expedited_shipping_fee").removeAttr("disabled");
                $("#wishlist_item_add_edit_modal #picture").attr('src',"{{asset('image/web/upload-pre.png')}}");
                $("#wishlist_item_add_edit_modal .error_upload_picture_wishlist").text('');
                 $("#wishlist_item_add_edit_modal .validation_display").remove();
                $("#wishlist_item_add_edit_modal .gift-field").removeClass('is-invalid');
            }

            $(document).ready(function () {
                $("body").on("click", "#wishlist_item_add_edit_modal #digital_purchase", function (e) {
                    if(!$(this).is(':checked')){
                        $("#wishlist_item_add_edit_modal #shipping_cost").removeAttr('disabled');
                    }else{
                        $("#wishlist_item_add_edit_modal #shipping_cost").prop('disabled',true);
                        $("#wishlist_item_add_edit_modal #shipping_cost").val(0);
                    }
                });

                $(".offer-gift").click(function () {
                    $("#ModalSlide .modal-footer-text").remove();
                });
                $('#file_input').change(function () {

                    var curElement = $('.image');

                    var reader = new FileReader();

                    var fileInput = document.getElementById('file');

                    var allowedExtensions =
                        /(\.jpg|\.jpeg|\.png|\.gif)$/i;
                    if (!allowedExtensions.exec(this.files[0].name)) {
                        $(".error_upload_picture_wishlist").text("Please upload file having extensions .jpeg/.jpg/.png/.gif only.");
                        this.files[0].value = '';
                        return false;
                    }else if (this.files[0].size > 2097152) {

                        $(".error_upload_picture_wishlist").text("Maximum 2MB file size is allowed.");
                        this.files[0].value = '';
                        return false;

                    }

                    reader.onload = function (e) {
                        // get loaded data and render thumbnail.
                        var image = new Image();
                        image.src = e.target.result;
                        image.onload = function () {
                            curElement.attr('src', e.target.result);
                            /*var height = this.height;
                            var width = this.width;
                            if (height < parseInt("{{config('constants.WISHLIST_IMG_HEIGHT')}}") || width < parseInt("{{config('constants.WISHLIST_IMG_WIDTH')}}")) {
                                //show width and height to user
                                $("#file_input").val("");
                                $(".error_upload_picture").text("{{config('constants.WISHLIST_IMG_ERR_MSG')}}");

                                return false;
                            } else {
                                curElement.attr('src', e.target.result);
                            }*/
                        };

                    };

                    // read the image file as a data URL.
                    reader.readAsDataURL(this.files[0]);
                });
                jQuery.validator.addMethod("imageValidation", function (value, element) {
                    var input = document.getElementById('file_input');
                    var msg = " ";
                    if (input.files[0]) {
                        var file = input.files[0];
                        var allowedExtensions =
                            /(\.jpg|\.jpeg|\.png|\.gif)$/i;
                        if (file.size > 2097152) {
                            return false;
                        } else if (!allowedExtensions.exec(input.name)) {
                            return false;
                        } else {
                            return true;
                        }
                    } else {
                        return true;
                    }

                }, "Only jpg, png, and gif images are allowed and it should be not greater than 2 MB");

                $("#wishlist_form").validate({
                    errorClass: 'is-invalid',
                });
                $(".eventForm").validate({
                    errorClass: 'is-invalid',
                    errorElement: "div",
                    errorPlacement: function (error, element) {
                        error.insertAfter(element.closest('div'));
                        error.addClass("validation_display");
                        //console.log(element);
                    },
                    rules: {
                        file_input: {
                            required: true,
                            imageValidation: true
                        }
                    }
                });
                jQuery.validator.addMethod("urlValidation", function (value, element) {
                    var regexp =  {{config('constants.VALID_URL')}};
                    var re = new RegExp(regexp);
                    return this.optional(element) || re.test(value);
                }, "Invalid URL");

                var offer_form_rest =$(".wishlist_url_form").validate({
                    errorClass: 'is-invalid text-danger',
                    rules:{
                        url : {
                            urlValidation : true,
                            required: true
                        }
                    }

                });
                $(".offer-gift").click(function () {
                    $("#ModalSlide #url_web").val("");
                    $("#ModalSlide #url_web").removeClass('is-invalid text-danger')
                    offer_form_rest.resetForm();
                });




                $('body').on('click', '.next_sc', function () {

                    if ($(".wishlist_url_form").valid()){
                        var regexp =  {{config('constants.VALID_URL')}};
                        var re = new RegExp(regexp);
                        if(re.test($("#url_web").val())) {
                            $("#eventForm .url_main").show();
                            $("#eventForm #type").val("url");
                            $("#eventForm #url").val($("#url_web").val());
                            $("#eventForm .url_main_manual").hide();
                            $("#ModalSlide").modal("toggle");
                            reset_wishlist_item_modal();
                            $("#wishlist_item_add_edit_modal").modal("toggle");
                            $("#wishlist_item_add_edit_modal .accept_main_field").remove();
                            $("#wishlist_item_add_edit_modal #to_user").val("{{$profile_detail->id}}");
                        }

                    }
                });
                $('body').on('click', '.no-link', function () {
                    $("#eventForm #type").val("manual");
                    $("#ModalSlide").modal("toggle");
                    $("#wishlist_item_add_edit_modal").modal("toggle");
                    $("#eventForm .url_main").hide();
                    $("#eventForm #url").val("");
                    reset_wishlist_item_modal();
                });
                $("body").on("click", ".save_wishlist_item", function (e) {
                    if ($(".eventForm").valid() && $('#file_input').val() !== '') {
                        var fd = new FormData();
                        $('body #eventForm input[type="file"]').each(function () {
                            //code
                            var input = document.getElementById($(this).attr('id'));
                            fd.append($(this).attr('name'), !input.files[0]);
                        });
                        var other_data = $('#eventForm').serializeArray();
                        $.each(other_data, function (key, input) {
                            if (input.name != "picture") {
                                fd.append(input.name, input.value);
                                console.log(input.name, input.value);
                            }
                        });
                        $(".save_wishlist_item").attr('disabled', true);

                        $(".validation_display").remove();

                        $.easyAjax({
                            url: "{{route('gift-offer-cart-add')}}",
                            type: "POST",
                            data: fd,
                            container: ".eventForm",
                            messagePosition: "",
                            file: true
                        });
                    } else {
                        if ($('#file_input').val() == '') {

                            $(".error_upload_picture_wishlist").text("Please upload a picture.");

                        }
                    }
                });
            });
        </script>
    @endif
    @if($same_profile)
        @include('frontend.gift_offer.gift_offer_js')
    @endif
@endsection
<!-- FOOTER SECTION END -->
