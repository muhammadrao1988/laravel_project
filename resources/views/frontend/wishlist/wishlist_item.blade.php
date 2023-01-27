@extends('layouts.layoutfront')
@section('css')
<style>
    .gift-field::placeholder, .gift-field-textarea::placeholder {color: #d6d4d4}
    /* Chrome, Safari, Edge, Opera */
    input.hide_counter::-webkit-outer-spin-button,
    input.hide_counter::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    .wishlist-sec { padding-top: 0; margin-top: -100px !important;}

    /* Firefox */
    input.hide_counter[type=number] {
        -moz-appearance: textfield;
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
</style>
@endsection
@section('fb_tag')
    <meta property="og:title"         content="{{$wishlist_detail->title." | ".$profile_detail->displayName}}" />
    <meta property="og:description" content="Give {{$profile_detail->displayName}} the perfect prezziez. Check out {{$profile_detail->displayName}}'s {{$wishlist_detail->title}} List.">
@endsection
@section('content')
    <!-- WISHLIST SECTION BEGIN -->
    <!-- PROFILE BANNER SECTION BEGIN -->
    <section class="profile-banner-sec">
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
    <section class="inner-main-sec wishlist-sec">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-12">
                    <div class="profile-sec-img">
                        @if($profile_detail->profile_image!="")
                            <img src="{{ asset("storage/uploads/profile_picture/".$profile_detail->profile_image)}}"
                                 class="img-fluid" alt="">
                        @else
                            <img src="{{asset('image/web/placeholder.png')}}" class="img-fluid"
                                 alt="avatar">
                        @endif
                        <h4 class="prof-heading">{{$wishlist_detail->title}}</h4>
                        <ul class="following-heading">
                            <li class="following-inner"><a href="{{route('profileUrl',$profile_detail->username)}}" class="following-text">by <span
                                            class="blue-des">{{$profile_detail->displayName}}</span></a></li>
                            @if($wishlist_detail->type!="general")
                                <li class="following-inner"><span class="line-color">|</span></li>
                                <li class="following-inner"><a href="javascript:;"
                                                               class="following-text">{{ date('M d,Y',strtotime($wishlist_detail->date))}}</a>
                                </li>
                            @endif
                        </ul>
                            @if($same_profile)
                            <ul class="share-icon">
                                <li class="share-icon-list"><a href="javascript:;" data-id="{{$wishlist_detail->id}}" data-wishlist-date="{{$wishlist_detail->date}}" data-wishlist-name="{{$wishlist_detail->title}}"  data-list-type="{{$wishlist_detail->type}}" class="share-icon-file edit_wishlist"
                                                               data-toggle="modal" data-target="#wish_list_add_edit"><i
                                                class="fa fa-cog" aria-hidden="true"></i></a>
                                </li>
                                <li class="share-icon-list">
                                    <div class="dropdown show">
                                        <a href="javascript:;" class="btn btn-secondary dropdown-toggle share_dropdown" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-share-alt" aria-hidden="true"></i>
                                        </a>

                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                            <a class="dropdown-item"  href="https://www.facebook.com/sharer/sharer.php?u={{route('show.wishlist.item',\App\Helpers\Common::encrypt_decrypt($wishlist_detail->id))}}&quote={{urlencode($wishlist_detail->title." | ".$profile_detail->displayName)}}" target="_blank"><i class="fa fa-facebook-official fac-col" aria-hidden="true"></i> Facebook</a>
                                            <a class="dropdown-item" target="_blank" href="https://twitter.com/intent/tweet?text={{urlencode($wishlist_detail->title." | ".$profile_detail->displayName)." ".route('show.wishlist.item',\App\Helpers\Common::encrypt_decrypt($wishlist_detail->id))}}"><i class="fa fa-twitter-square twit-col" aria-hidden="true"></i> Twitter </a>
                                            <a class="dropdown-item" href="mailto: ?body={{route('show.wishlist.item',\App\Helpers\Common::encrypt_decrypt($wishlist_detail->id))}},&subject={{$wishlist_detail->title." | ".$profile_detail->displayName}}"><i class="fa fa-envelope-square email-col" aria-hidden="true"></i> Email</a>
                                        </div>
                                    </div>
                                    {{--<a href="javascriptvoid:(0)" class="share-icon-file"><i
                                                class="fa fa-share-alt" aria-hidden="true"></i></a>--}}
                                </li>
                                {{--<li class="share-icon-list"><a href="javascriptvoid:(0)" class="share-icon-file"><i
                                                class="fa fa-share-alt" aria-hidden="true"></i></a></li>--}}
                            </ul>
                            @endif
                    </div>
                </div>
            </div>
            @if($same_profile && count($wish_list_items)==0)
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-12">
                        <div class="wish-yet-sec">
                            <a href="javascriptvoid:(0)" class="add-prezziez" data-toggle="modal"
                               data-target="#ModalSlide">ADD A PREZZIE</a>
                        </div>
                    </div>
                </div>
            @else
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-12">
                        <div class="filter-selector">
                            <select name="sort_by" class="sort_by">
                                <option value="new" {{request()->get('sort_by') == "new" ? "selected" : ""}}>From most recently added</option>
                                <option value="low" {{request()->get('sort_by') == "low" ? "selected" : ""}}>From Low to High</option>
                                <option value="high" {{request()->get('sort_by') == "high" ? "selected" : ""}}>From High to Low</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row overlay-boxes-row">
                    @if($same_profile)
                        {{--<div class="col-md-4 col-sm-4 col-12 blue-boxes-padd">
                            <div class="gift-card-box">
                                <div class="blue-img">
                                    <img src="{{asset('image/web/bl1.png')}}" alt="image"
                                         class="img-fluid">
                                    <div class="blue-img-icon">
                                        <a href="javascript:;" class="blue-img-text" data-toggle="modal"
                                           data-target="#wish_list_add_edit"><i class="fa fa-plus" aria-hidden="true"></i></a>
                                    </div>
                                </div>
                                <h4 class="gift-card-heading">Create Wishlist</h4>
                            </div>
                        </div>--}}
                        <div class="column blue-boxes-padd wishlist_add_main" style="cursor: pointer">
                            <div class="card">
                                <div class="content">
                                    <div class="front">

                                        <div class="wish-yet-sec blue-img">
                                            <img src="{{asset('image/web/bl1.png')}}" alt="image"
                                                 class="img-fluid">
                                            <div class="blue-img-icon" style="top: -20%">
                                                <a href="javascript:;" class="blue-img-text"> <i class="fa fa-plus" aria-hidden="true"></i></a>
                                            </div>

                                            <h1 class="gift-card-heading text-center mt-5">Add Prezzie
                                            </h1>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @foreach($wish_list_items as $wishList)
                        @php
                            if($wishList->accept_donation==1)
                            {
                                $total = ($wishList->price * $wishList->quantity) + $wishList->shipping_cost + $wishList->service_fee + $wishList->tax_rate;
                            }
                            else{
                                $total = ($wishList->price * $wishList->quantity);
                            }
                        @endphp
                        <div class="column">
                            <div class="card">
                                <div class="content">
                                    <div class="front">
                                        <!-- <div class="ribbon-bar">
                                          <p class="don-acc">DONATION ACCEPTED</p>
                                        </div> -->
                                        <div class="front-box-img">
                                            @if($wishList->picture!="")
                                                @php($picture = asset("storage/uploads/wishlist_item/".$wishList->picture))
                                            @else
                                                @php($picture = asset('image/web/prezziez_logo_green.jpg'))
                                            @endif
                                                <img  class="profile wishlist_item_img img-fluid" src="{{$picture}}" alt="image">

                                            <div class="front-box-inner">
                                                <div class="back from-right">
                                                    <a href="javascriptvoid:(0)" class="overlay-detail-buttn">View
                                                        Details</a>
                                                </div>
                                                <div class="back from-bottom">
                                                    <ul class="bottom-animation">
                                                        <li class="bottom-animation-text"><strong>Quantity</strong>: {{$wishList->quantity}}</li>
                                                        @if($wishList->accept_donation!=1)
                                                            <li class="bottom-animation-text"><strong>Shipping Cost</strong>: ${{\App\Helpers\Common::numberFormat($wishList->shipping_cost)}}</li>
                                                        @endif
                                                        @if($wishlist_detail->type !="general")
                                                            <li class="bottom-animation-text"><strong>Event Date</strong>: {{$wishlist_detail->date}}</li>
                                                        @endif
                                                        <li class="bottom-animation-text"><strong>Wishlist Type</strong>: {{ucfirst($wishlist_detail->type)}}</li>
                                                        @if($same_profile)
                                                            <li class="bottom-animation-text">
                                                                @if($wishList->collected_amount <=0)
                                                                    <a class="edit_wishlist_item" href="javascript:;" data-id="{{$wishList->id}}" data-gift-name="{{$wishList->gift_name}}" data-price="{{$wishList->price}}"
                                                                       data-quantity="{{$wishList->quantity}}" data-accept-donation="{{$wishList->accept_donation}}" data-picture="{{$picture}}" data-merchant="{{$wishList->merchant}}"
                                                                       data-digital-purchase="{{$wishList->digital_purchase}}" data-gift-details="{{$wishList->gift_details}}" data-shipping-cost="{{$wishList->shipping_cost}}"
                                                                       data-expedited-shipping-cost="{{($wishList->expedited_shipping_fee > 0 ? $wishList->expedited_shipping_fee : "")}}" data-url="{{$wishList->url}}"
                                                                       title="Edit"><i class="fa fa-pencil"></i> </a>
                                                                    <a class="ml-1 delete_wishlist_item" data-toggle="modal" data-target="#deleteModal"  data-id="{{$wishList->id}}" href="javascript:;" title="Delete"> <i class="fa fa-trash"></i> </a>
                                                                @endif
                                                            </li>
                                                        @endif
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <h6 class="front-heading">{{$wishList->gift_name}}</h6>
                                        <p class="front-text">${{\App\Helpers\Common::numberFormat($total)}}</p>


                                        @if($wishList->accept_donation==1)
                                            @if($wishList->collected_amount > 0)
                                                @php($percentage = floor(($wishList->collected_amount/$total) * 100))
                                             @else
                                                @php($percentage = 0)
                                             @endif
                                        <div class="progress">
                                            <div
                                                 class="progress-bar progress-bar-success progress-bar-striped active" data-percentage="{{$percentage}}"
                                                 role="progressbar" aria-valuenow="0" aria-valuemin="0"
                                                 aria-valuemax="100" style="width: 0%">
                                                <span id="current-progress"></span>
                                            </div>
                                        </div>
                                        @else
                                           <br><br>
                                        @endif
                                        <div class="wishlist-buttons">
                                        @if($total <= $wishList->collected_amount)
                                                @if($same_profile)
                                                    <a href="{{route('show.wishlist.item.detail',\App\Helpers\Common::encrypt_decrypt($wishList->id))}}" class="front-buttn">Track Details</a>
                                                @else
                                                    <a href="javascript:;" class="front-buttn">Completed</a>
                                                @endif
                                        @else
                                            @if($wishList->accept_donation==1)
                                                <a data-name="{{$wishList->gift_name}}" data-remaining="{{\App\Helpers\Common::numberFormat($total -$wishList->collected_amount )}}" data-collected-amount="{{$wishList->collected_amount=="" ? 0 : \App\Helpers\Common::numberFormat($wishList->collected_amount)}}" data-total="{{\App\Helpers\Common::numberFormat($total)}}" href="javascript:;" class="front-buttn contr-btn" data-attr="{{$wishList->id}}">CONTRIBUTE</a>
                                            @else
                                                <a href="{{route('add.to.cart',['id'=>$wishList->id])}}" class="front-buttn" id="add-to-cart">ADD TO CART</a>
                                            @endif
                                        @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
    <!-- MODAL 1 Add Url-->
    @include('frontend.wishlist.wishlist_add_url_modal')
    <!-- MODAL 1 END -->
    <!-- MODAL Wishlist Item BEGIN -->
    @include('frontend.wishlist.wishlist_item_add_edit_modal')
    <!-- MODAL 2 END -->
    <!-- MODAL Wishlist add -->
    @include('frontend.wishlist.wishlist_add_edit_modal')
    <!-- MODAL  END -->
    <!-- MODAL Wishlist title date -->
    @include('frontend.wishlist.wishlist_title_modal')
    <!-- MODAL END -->
    <!-- Delete Modal -->
    <div class="modal modal1 fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-slideout" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="delete_modal_form" method="POST">
                        {{csrf_field()}}
                        <input type="hidden" name="id" id="id" value="">
                        <input type="hidden" name="wishlist_id" id="wishlist_id" value="{{\App\Helpers\Common::encrypt_decrypt($wishlist_detail->id,'encrypt')}}">
                        <h5 class="modal-title add-prezziez-heading align-right" id="exampleModalLabel">Delete Record</h5>
                        <p class="add-prezziez-text">Do you really want to delete this record?</p>
                        <br>
                        <button type="submit" style="margin: 0 auto" class="add-next">Confirm</button>
                        <br>
                        <a href="javascript:;"  data-dismiss="modal" class="mt-2 text-center" style="display: block">Cancel</a>
                    </form>
                    {{--<h5 class="modal-title add-prezziez-heading align-right" id="exampleModalLabel">Add a Prezziez</h5>
                    <p class="add-prezziez-text">Paste a link from anywhere on the web</p>
                    <input type="text" class="prezziez-field" placeholder="https: //">
                    <a href="javascriptvoid:(0)" class="add-next">NEXT</a>--}}
                </div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>
    <!-- MODAL 1 END -->
    {{-- Contribution Modal --}}
    <div class="modal modal3 fade" id="contribution-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-slideout" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5 class="modal-title add-prezziez-heading align-right" id="exampleModalLabel"><span
                                class="prezziez-setting modal-wishlist-name"></span> Contribution</h5>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-12">
                            <form id="contribution-form" method="POST" action="{{route('contribute')}}">
                                <input type="hidden" name="wishlist_item_id" value="" id="wishlist_item">

                            <div class="selection-popup-boxes">
                                <ul>
                                    <li><input type="checkbox" id="cb2" value="registry" name="gift_type"/>
                                        <label for="cb2">
                                            <div class="popup-inner">
                                                <img src="{{asset('image/web/pop2.png')}}" alt="image"
                                                     class="img-fluid">
                                                <div class="popup-inner-content">
                                                    <h6 class="popup-inner-heading">Total Amount:</h6>
                                                    <p class="popup-inner-text" id="total-amount"></p>
                                                </div>
                                            </div>
                                        </label>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="cb3" value="experiences" name="gift_type"/>
                                        <label for="cb3">
                                            <div class="popup-inner">
                                                <img src="{{asset('image/web/pop3.png')}}" alt="image"
                                                     class="img-fluid">
                                                <div class="popup-inner-content">
                                                    <h6 class="popup-inner-heading">Total Contributed:</h6>
                                                    <p class="popup-inner-text" id="contributed-amount"></p>
                                                </div>
                                            </div>
                                        </label>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="cb4" value="events" name="gift_type"/>
                                        <label for="cb4">
                                            <div class="popup-inner">
                                                <img src="{{asset('image/web/pop5.png')}}" alt="image"
                                                     class="img-fluid">
                                                <div class="popup-inner-content">
                                                    <h6 class="popup-inner-heading">Amount Left:</h6>
                                                    <p class="popup-inner-text" id="amount-left"></p>
                                                </div>
                                            </div>
                                        </label>
                                    </li>
                                    <li>
                                        <input type="checkbox" id="cb1" value="general" name="gift_type"/>
                                        <label for="cb1">
                                            <div class="popup-inner">
                                                <img src="{{asset('image/web/pop1.png')}}" alt="image"
                                                     class="img-fluid">
                                                <div class="popup-inner-content">
                                                    <h6 class="popup-inner-heading">Amount</h6>
                                                    <p class="popup-inner-text">
                                                        <input type="number" value="" name="amount" id="contributed_amount" placeholder="Enter amount" required>
                                                    </p>
                                                </div>
                                            </div>
                                        </label>
                                    </li>
                                </ul>
                            </div>
                            </form>
                        </div>
                    </div>
                    <a href="javascript:;" class="add-next contribute_submit">CONTRIBUTE</a>
                </div>
            </div>
        </div>
    </div>
    <div class="loading" style="display: none">
        <div class="loader"></div>
        <div class="loaderoverlay"></div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/web/aksFileUpload.js')}}"></script>
    <script src="{{ asset('js/helper.js')}}"></script>
    <script>

        @if($same_profile)
        $(document).ready(function () {

            $(".wishlist_add_main").click(function () {
                $("#ModalSlide").modal('show');
                $("#ModalSlide #url_web").val("");
            })

            //wishlist operation
            $('.next_wishlist').click(function () {

                var checkedNum = $('input[name="gift_type"]:checked');
                if (!checkedNum.length) {
                    // User didn't check any checkboxes
                    alert("Please select one type");
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
            $(".submit_wishlist").click(function (e) {

                var data = $('#wish_list_title_modal #wishlist_form').serialize();
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
                    $(".submit_wishlist").text("Edit LIST");
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
            $(".edit_wishlist").click(function () {
                var wish_list_type = $(this).attr('data-list-type');
                var wish_list_name = $(this).attr('data-wishlist-name');
                var wish_list_id = $(this).attr('data-id');
                var wish_list_date = $(this).attr('data-wishlist-date');
                var group = "#wish_list_add_edit input:checkbox[name='gift_type']";
                $(group).prop("checked", false);
                $("#wish_list_add_edit input[value='" + wish_list_type + "']").prop('checked', true);
                $("#wish_list_title_modal #id").val(wish_list_id);
                $("#wish_list_title_modal #title").val(wish_list_name);
                $("#wish_list_title_modal #type_date").val(wish_list_date);
                $("#wish_list_title_modal .prezziez-setting").text('Edit Wishlist');
                $("#wish_list_title_modal .submit_wishlist").text('Edit List');

            });
            $("body .edit_wishlist_item").click(function () {
                var gift_name = $(this).attr('data-gift-name');
                var price = $(this).attr('data-price');
                var id = $(this).attr('data-id');
                var url = $(this).attr('data-url');
                var quantity = $(this).attr('data-quantity');
                var merchant = $(this).attr('data-merchant');
                var accept_donation = $(this).attr('data-accept-donation');
                var picture = $(this).attr('data-picture');
                var digital_purchase = $(this).attr('data-digital-purchase');
                var gift_details = $(this).attr('data-gift-details');
                var shipping_cost = $(this).attr('data-shipping-cost');
                var exp_shipping_cost = $(this).attr('data-expedited-shipping-cost');
                var group = "#wish_list_add_edit input:checkbox[name='gift_type']";
                $(group).prop("checked", false);
                if(accept_donation==1){
                    accept_donation = true;
                }else{
                    accept_donation = false;
                }
                if(digital_purchase==1){
                    digital_purchase = true;
                    $("#wishlist_item_add_edit_modal #shipping_cost").prop('disabled',true);
                    $("#wishlist_item_add_edit_modal #expedited_shipping_fee").prop('disabled',true);
                }else{
                    digital_purchase = false;
                    $("#wishlist_item_add_edit_modal #shipping_cost").removeAttr('disabled');
                    $("#wishlist_item_add_edit_modal #expedited_shipping_fee").removeAttr('disabled');
                }

                $("#wishlist_item_add_edit_modal input:checkbox[name='accept_donation']").prop('checked', accept_donation);
                $("#wishlist_item_add_edit_modal input:checkbox[name='digital_purchase']").prop('checked', digital_purchase);
                $("#wishlist_item_add_edit_modal #id").val(id);
                $("#wishlist_item_add_edit_modal #url").val(url);
                $("#wishlist_item_add_edit_modal .url_main_manual").show();
                $("#wishlist_item_add_edit_modal #merchant").val(merchant);
                $("#wishlist_item_add_edit_modal #gift_details").val(gift_details);
                $("#wishlist_item_add_edit_modal #quantity").val(quantity);
                $("#wishlist_item_add_edit_modal #price").val(price);
                $("#wishlist_item_add_edit_modal #gift_name").val(gift_name);
                $("#wishlist_item_add_edit_modal #shipping_cost").val(shipping_cost);
                $("#wishlist_item_add_edit_modal #expedited_shipping_fee").val(exp_shipping_cost);
                $("#wishlist_item_add_edit_modal #picture").attr('src',picture);
                $("#wishlist_item_add_edit_modal .error_upload_picture_wishlist").text('');
                $("#wishlist_item_add_edit_modal #file_input").val(null);
                $("body #wishlist_item_add_edit_modal").modal("toggle");

            })
            $(".delete_wishlist_item").click(function () {
                var id  = $(this).attr('data-id');
                $("#deleteModal #delete_modal_form #id").val(id);
                $("#deleteModal #delete_modal_form").attr("action","{{route('delete.wishlist.item')}}");
            })


            $('#file_input').change(function () {
                $(".error_upload_picture_wishlist").text("");
                var curElement = $('.image');

                var reader = new FileReader();

                var fileInput = document.getElementById('file');

                var allowedExtensions =
                    /(\.jpg|\.jpeg|\.png|\.gif|\.webp)$/i;
                if (!allowedExtensions.exec(this.files[0].name)) {
                    $(".error_upload_picture_wishlist").text("Please upload file having extensions .jpeg/.jpg/.png/.gif/.webp only.");
                    this.files[0].value = '';
                    $("#wishlist_item_add_edit_modal #picture").attr('src',"{{asset('image/web/upload-pre.png')}}");
                    return false;
                }else if (this.files[0].size > 2097152) {
                    $(".error_upload_picture").text("Maximum 2MB file size is allowed.");
                    this.files[0].value = '';
                    $("#wishlist_item_add_edit_modal #picture").attr('src',"{{asset('image/web/upload-pre.png')}}");
                    return false;

                }

                reader.onload = function (e) {
                    // get loaded data and render thumbnail.
                    var image = new Image();
                    image.src = e.target.result;
                    image.onload = function () {
                        curElement.attr('src', e.target.result);
                        $(".error_upload_picture_wishlist").text("");
                        /*var height = this.height;
                        var width = this.width;
                        var req_height = "{{config('constants.WISHLIST_IMG_HEIGHT')}}";
                        var req_width = "{{config('constants.WISHLIST_IMG_WIDTH')}}";

                        if (height != parseInt(req_height) || width != parseInt(req_width)) {
                            //show width and height to user
                            $("#file_input").val("");
                            $(".error_upload_picture").text("{{config('constants.WISHLIST_IMG_ERR_MSG')}}");
                            return false;
                        }else{

                        }*/
                    };

                };

                // read the image file as a data URL.
                reader.readAsDataURL(this.files[0]);
            });

            jQuery.validator.addMethod("acceptDonation", function (value, element) {
                var checkedNum = $('input[name="accept_donation"]:checked');
                var total_price = parseInt($('#quantity').val()) * parseInt($("#price").val());

                if (checkedNum.length > 0) {
                    if (total_price < 150) {
                        return false;
                    } else {
                        return true;
                    }
                } else {
                    return true;
                }

            }, "Must have at least $150 price to enable this option");

            jQuery.validator.addMethod("expeditedShipping", function (value, element) {

                var shippingPrice = parseFloat($('#shipping_cost').val());
                var expShippingPrice = parseFloat($('#expedited_shipping_fee').val());

                if (expShippingPrice!="" && shippingPrice >= expShippingPrice) {
                    return false;
                } else {
                    return true;
                }

            }, "Expedited shipping price must be greater than shipping cost");


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

            jQuery.validator.addMethod("urlValidation", function (value, element) {
                var regexp =  {{config('constants.VALID_URL')}};
                var re = new RegExp(regexp);
                return this.optional(element) || re.test(value);
            }, "Invalid URL");

            jQuery.validator.addMethod("greaterThanZero", function(value, element) {
                return this.optional(element) || (parseFloat(value) >= 0);
            }, "Value must be greater than zero");

            $("#wishlist_form").validate({
                errorClass: 'is-invalid text-danger',
                rules:{
                    url : {
                        urlValidation : true
                    }
                }

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
                    accept_donation: {
                        acceptDonation: true
                    },
                    expedited_shipping_fee: {
                        expeditedShipping: true
                    },
                    url : {
                        urlValidation : true
                    },
                    price : {
                        greaterThanZero : true
                    },
                    shipping_cost : {
                        greaterThanZero : true
                    },
                    quantity : {
                        greaterThanZero : true
                    }
                    /*file_input: {
                        required:true,
                        imageValidation: true
                    }*/
                }
            });
            $("aks-file-upload").aksFileUpload({
                fileUpload: "#uploadfile",
                dragDrop: true,
                maxSize: "2 MB",
                fileType: ["jpg", "jpeg", "png", "bmp", "gif"],
                multiple: false,
                input: "#picture",
                label: "Upload a Picture"

            });
            $("body").on("click", "#wishlist_item_add_edit_modal #digital_purchase", function (e) {
                if(!$(this).is(':checked')){
                   $("#wishlist_item_add_edit_modal #shipping_cost").removeAttr('disabled');
                   $("#wishlist_item_add_edit_modal #expedited_shipping_fee").removeAttr('disabled');
                }else{
                    $("#wishlist_item_add_edit_modal #shipping_cost").prop('disabled',true);
                    $("#wishlist_item_add_edit_modal #expedited_shipping_fee").prop('disabled',true);
                    $("#wishlist_item_add_edit_modal #shipping_cost").val(0);
                    $("#wishlist_item_add_edit_modal #expedited_shipping_fee").val(0);
                    /*z.art*/
                    $("#wishlist_item_add_edit_modal #shipping_cost-error").remove();
                    $("#wishlist_item_add_edit_modal #shipping_cost").removeClass('is-invalid');
                    $("#wishlist_item_add_edit_modal #expedited_shipping_fee-error").remove();
                    $("#wishlist_item_add_edit_modal #expedited_shipping_fee").removeClass('is-invalid');
                    /*z.art*/
                }
            });

            $("body").on("click", ".save_wishlist_item", function (e) {
                $(".error_upload_picture_wishlist").text("");
                var id = $(".eventForm #id").val();

                /*if(id == "" &&  $('#file_input').val() == ''){
                    $(".error_upload_picture_wishlist").text("Please upload a picture");
                    $(".eventForm").valid();
                    return false;
                }*/

                if ($(".eventForm").valid()){
                    var fd = new FormData();
                    $('body #eventForm input[type="file"]').each(function () {
                        //code
                        var input = document.getElementById($(this).attr('id'));
                        fd.append($(this).attr('name'), !input.files[0]);
                    });
                    var other_data = $('#eventForm').serializeArray();
                    $.each(other_data, function (key, input) {
                        fd.append(input.name, input.value);
                        //console.log(input.name, input.value);
                    });
                    //$(".validation_display").remove();

                    $.easyAjax({
                        url: "{{ route('add.wishlist.item') }}",
                        type: "POST",
                        data: fd,
                        container: ".eventForm",
                        messagePosition: "inline",
                        file: true
                    });
                }
                else{

                    /*if($('#file_input').val() == '' && id==""){

                        $(".error_upload_picture_wishlist").text("Please upload a picture");
                    }else{
                        $(".error_upload_picture_wishlist").text("");
                    }*/
                }
            });
            $('body').on('click', '.no-link', function () {
                $("#eventForm #type").val("manual");
                $("#ModalSlide").modal("toggle");
                $("#wishlist_item_add_edit_modal").modal("toggle");
                $("#eventForm .url_main").hide();
                $("#eventForm #url").val("");
                $("#eventForm .url_main_manual").show();
                reset_wishlist_item_modal();
            });
            $('body').on('click', '.next_sc', function () {
                if ($("#wishlist_form").valid()) {
                    $("#eventForm #type").val("url");
                    $("#eventForm #url").val($("#url_web").val());

                    $("#ModalSlide").modal("toggle");
                    $("#eventForm .url_main_manual").hide();
                    reset_wishlist_item_modal();
                    $("#wishlist_item_add_edit_modal").modal("toggle");

                }
            });




        })
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
        @endif
    </script>
    <script>
        $(function () {
            @if(!empty($wishList->giftee_wishlist_id))
                $(".sort_by").change(function () {
                    var sort_by = $(this).val();
                    location.href = "{{route('show.wishlist.item',\App\Helpers\Common::encrypt_decrypt($wishList->giftee_wishlist_id))}}?sort_by="+sort_by;
                })
            @endif

            $('form input').keydown(function (e) {
                if (e.keyCode == 13) {
                    e.preventDefault();
                    return false;
                }
            });

            $(".progress-bar").each(function () {
               var  current_progress = $(this).attr('data-percentage');
                var ref = $(this);
                $(ref)
                    .css("width", current_progress + "%")
                    .attr("aria-valuenow", current_progress)
                    .text(current_progress + "% Complete");
               /* var interval = setInterval(function () {

                    console.log(current_progress);

                    if (current_progress >= 50)
                        clearInterval(interval);
                }, 1000);*/
            })

        });
        $('.contr-btn').click(function(){

            var wishlist_item_id = $(this).attr('data-attr');
            var collected_amount = $(this).attr('data-collected-amount');
            var total_amount = $(this).attr('data-total');
            var remaining_amount = $(this).attr('data-remaining');
            var name = $(this).attr('data-name');
            $('#contribution-modal #total-amount').html("$"+total_amount);
            $('#contribution-modal #contributed-amount').html("$"+collected_amount);
            $('#contribution-modal #amount-left').html("$"+remaining_amount);
            $('#contribution-modal #wishlist_item').val(wishlist_item_id);
            $('#contribution-modal #contributed_amount').val('');
            $('#contribution-modal .modal-wishlist-name').html(name);
            $("#contribution-modal").modal("show");
        });
        $(".contribute_submit").click(function () {
            var amount = $("#contribution-modal #contributed_amount").val();
            if(amount==""){
                toastr.error("Please add amount", "Error");
                return false;
            }
            var data2= $("#contribution-form").serialize();
            $(".contribute_submit").attr('disabled',true);
            $(".loading").show();
            $.ajax({
                url: "{{route('contributed-cart-add')}}",
                dataType: "json",
                type: "GET",
                data: data2
            }).then(function (data) {
                $(".loading").hide();
                toastr.success("Please process the payment", "Success");
                setTimeout(function () {
                    location.href = "{{route('contributed-checkout')}}";

                }, 1000);

            }).fail(function (error) {
                $(".loading").hide();
                $(".contribute_submit").removeAttr('disabled');
                //$("#result").text(error.responseJSON.message);
                if (error.responseJSON.hasOwnProperty('errors')) {
                    var error_msg = "";

                    for (var prop in error.responseJSON.errors) {
                        console.log(prop);

                        $(error.responseJSON.errors[prop]).each(function (val, msg) {
                            toastr.error(msg, "Error");
                        });
                    }
                    //$("#result").text("");

                } else {
                    toastr.error(error.responseJSON.message, "Error");
                    //$("#result").text("");
                }

            });
        })
        $('.overlay-detail-buttn').on("click", function (e) {
            e.preventDefault();
            $('.back.from-bottom').removeClass('abc-opacity-1')
            let parent_element = $(this).closest('.back.from-right');
            $(parent_element).next('.back.from-bottom').addClass('abc-opacity-1');
        });
        $(document).mouseup(function (e) {
            var container = $(".back.from-bottom");
            if (!container.is(e.target) && container.has(e.target).length === 0) {
                container.removeClass('abc-opacity-1');
            }
        });
    </script>

@endsection
<!-- FOOTER SECTION END -->
