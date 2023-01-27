@extends('layouts.layoutfront')
@section('css')
    <style>
        .add-to-wishlist { cursor: pointer}
    </style>
@endsection
@section('content')
    <div class="content main-slider">
        <div class="carousel-nav">
            @php
                $i=0
            @endphp
            @foreach ($categories as $category )
                <a href="#" data-slug="{{strtolower(str_replace(" ","-",$category->title))}}" class="col category {{$i==0 ? "active" : ""}}" id="{{$category->id}}"><span
                            class="line-width"></span>{{$category->title}}</a>
                @php
                    $i++
                @endphp
            @endforeach
        </div>
        <div class="owl-carousel owl-1">
            @foreach ($categories as $category )
                <div class="media-29101 w-100">
                    <div class="img">
                        <img src="{{ asset("storage/uploads/category/".$category->image_path)}}" alt="Image"
                             class="img-fluid">
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <section class="gift-ideas-page-sec">
        <div class="container">
            <div class="row heading-boxes">
               <!--  <div class="col-md-6 col-sm-6 col-12"> -->
                    <h4 class="ideas-heading">Gift Guide</h4>
                <!-- </div> -->
                {{--<div class="col-md-6 col-sm-6 col-12">
                  <div class="gift-term">
                  <div class="login-signup-buttns login-inner-buttons">
                    <div id="background">
                      <a href="javascriptvoid:(0)" class="login">
                        Gift Guides
                        <div><span>Gift Guides</span></div>
                      </a>
                    </div>
                  </div>
                </div>
                </div>--}}
            </div>
            <div class="row gift-boxes-row" id="ideas-list">
            </div>
            <div class="row justify-content-center">
                <div class="spinner-border row " role="status">
                    <span class="sr-only ml-auto">Loading...</span>
                </div>
            </div>
            <div class="row">
                <button class="col-12 pt-3 pb-3 front-buttn text-center cursor-pointer mb-4"
                        id="show-more">See More
                </button>
            </div>
        </div>
    </section>
    <div class="modal modal1 fade" id="ModalSlide" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2"
         aria-hidden="true">
        <div style="max-width: 50%" class="modal-dialog modal-lg" role="document" id="modal">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="wishlist_form" method="POST">
                        {{csrf_field()}}
                        <h5 class="modal-title add-prezziez-heading align-right" id="exampleModalLabel">Add to
                            wishlist</h5>
                        <input type="hidden" name="selected_idea" value="" id="selected_idea">
                        <input type="hidden" name="type" value="gift_idea" id="type">
                        <input type="hidden" name="picture_name" value="" id="picture">
                        <div class="row">
                            <div class="col-md-12 url_main_manual">
                                <div class="confirm-form">
                                    <label class="gift-text">Select Wishlist *</label>
                                    <select required data-name="Wishlist" name="giftee_wishlist_id" class="prezziez-field">
                                        <option value="">Select</option>
                                        @foreach ($wishlist as $wishlist)
                                            <option value="{{\App\Helpers\Common::encrypt_decrypt($wishlist->id)}}">{{$wishlist->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 url_main_manual">
                                <div class="confirm-form">
                                    <label class="gift-text">URL *</label>
                                    <input required  id="url" name="url" class="gift-field" type="text"
                                           placeholder="">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-12">
                                <div class="confirm-form">
                                    <label class="gift-text">Gift Name *</label>
                                    <input required id="gift_name" name="gift_name" data-name="gift name"
                                           data-validation-name="gift name" class="gift-field" type="text"
                                           placeholder="Ex. Laptop, shoes. etc">
                                    <div class="invalid-feedback validation_display"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-12">
                                <div class="confirm-form">
                                    <label class="gift-text">Price *</label>
                                    <input id="price" required name="price" class="gift-field" type="number"
                                           data-name="price" data-validation-name="price"   placeholder="$1000">
                                    <div class="invalid-feedback validation_display"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-12">
                                <div class="confirm-form">
                                    <label class="gift-text">Shipping Cost:</label>
                                    <input id="shipping_cost" required name="shipping_cost" class="gift-field"
                                           data-name="shipping cost" data-validation-name="shipping cost" type="number" placeholder="$20">
                                    <div class="invalid-feedback validation_display"></div>

                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-12 accept_main_field">
                                <div class="confirm-form">
                                    <label class="gift-text">Expedited Shipping:</label>
                                    <input id="expedited_shipping_fee"   name="expedited_shipping_fee" class="gift-field"
                                           data-name="Expedited shipping" data-validation-name="Expedited shipping" type="number" placeholder="$25">
                                    <div class="invalid-feedback validation_display"></div>

                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-12">
                                <div class="confirm-form">
                                    <label class="gift-text">Quantity:</label>
                                    <input id="quantity" required name="quantity" class="gift-field"
                                           data-name="quantity" data-validation-name="quantity" type="number" placeholder="Quantity">
                                    <div class="invalid-feedback validation_display"></div>

                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-12">
                                <div class="confirm-form">
                                    <label class="gift-text">Merchant:</label>
                                    <input name="merchant" required id="merchant" class="gift-field" type="text"
                                           data-name="merchant" data-validation-name="merchant" placeholder="Exp, Dell, Apple, Nike. Etc">
                                    <div class="invalid-feedback validation_display"></div>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-12 accept_main_field">
                                <div class="confirm-form">
                                    <p class="accept-donation">
                                        <input id="accept_donation" name="accept_donation" value="1"
                                               type="checkbox" data-name="Accept Donation"
                                               data-validation-name="Accept Donation"
                                               class="accept-donation-check"> Accept contributions for this gift
                                    <div class="invalid-feedback validation_display"></div>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-12">
                                <div class="confirm-form">
                                    <p class="accept-donation">
                                        <input type="checkbox" class="accept-donation-check"
                                               id="digital_purchase" name="digital_purchase" value="1"
                                               data-validation-name="Digital Purchase"
                                               data-name="Digital Purchase"> Is this item a digital Purchase?
                                    <div class="invalid-feedback validation_display"></div>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-12">
                                <div class="confirm-form">
                                    <label class="gift-text">Gift Details:</label>
                                    <textarea placeholder="Please enter the size/color/variation/style etc. of the item you want. Failure to do so may result in the default item being ordered" cols="5" rows="5" class="gift-field-textarea" name="gift_details" id="gift_details"
                                              data-name="Gift Details"></textarea>
                                    <div class="invalid-feedback validation_display"></div>
                                </div>
                            </div>
                        </div>
                        <a href="javascript:;" class="add-next next_sc submit_wishlist">NEXT</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(function () {
            @if($show_selected_category)
            setTimeout(function () {
                $("a.category[data-slug='{{$show_selected_category}}']").trigger("click");
            },1000)

            @endif
            //$('.spinner-border').hide();
            var owl = $('.owl-1');
            owl.owlCarousel({
                loop: false,
                margin: 0,
                nav: false,
                dots: false,
                items: 1,
                smartSpeed: 1000,
                autoplay: false,
                navText: ['<span class="icon-keyboard_arrow_left">', '<span class="icon-keyboard_arrow_right">']
            });
            var carousel_nav_a = $('.carousel-nav a');
            carousel_nav_a.each(function (slide_index) {
                var $this = $(this);
                $this.attr('data-num', slide_index);
                $this.click(function (e) {
                    owl.trigger('to.owl.carousel', [slide_index, 1500]);
                    e.preventDefault();
                });
            })
            owl.on('changed.owl.carousel', function (event) {
                carousel_nav_a.removeClass('active');
                $(".carousel-nav a[data-num=" + event.item.index + "]").addClass('active');
            })
            loadMoreData(1);
        });

        //pagination script
        var category_id = "{{ isset($categories[0]->id) ? $categories[0]->id : 0}}";
        var page = 1;
        var is_completed = 0;
        var gift_idea = '';
        var price = '';

        $('.category').click(function () {
            category_id = $(this).attr("id");
            page = 1;
            loadMoreData(1,true);
        })

        $('body').on('click', '#show-more', function () {
            page++; //page number increment
            loadMoreData(page, false);

        });

        function loadMoreData(page, hide_html = true) {
            if (is_completed == 0) {

                is_completed = 1;
                if (hide_html) {
                    $("#ideas-list").html("");
                }
                $.ajax(
                    {
                        url: "{{route('getgiftideas')}}",
                        type: "get",
                        data: {category_id: category_id, page: page},
                        beforeSend: function () {
                            $('.spinner-border').show();
                            if (hide_html) {
                                $(".spinner-border").show();
                            }
                            $("#show-more").attr("disabled", true);
                            $("#show-more").text("Loading..");
                        }
                    })
                    .done(function (data) {

                        var last_page = data.last_page;
                        $('.spinner-border').hide();
                        is_completed = 0;
                        $("#show-more").removeAttr("disabled");
                        $("#show-more").text("See More");


                        if (parseInt(last_page) <= parseInt(page)) {
                            $("#show-more").hide();
                        } else {
                            $("#show-more").show();
                        }


                        if (data.html == "") {
                            if (page == 1) {
                                $("#ideas-list").html("<p class='text-center'>No record found</p>");
                            }
                            return;
                        }
                        if (data.html != "") {
                            $("#ideas-list").append(data.html);
                        }
                    })
                    .fail(function (jqXHR, ajaxOptions, thrownError) {
                        $('.spinner-border').hide();
                        is_completed = 0;
                        alert('server not responding...');

                    });
            }
        }

        $(".add-next").click(function (e) {
            if ($("#wishlist_form").valid()) {
                $('#selected_idea').val(gift_idea);
                var data = $('#wishlist_form').serialize();
                $(".submit_wishlist").attr('disabled', true);
                $(".submit_wishlist").text("Please wait, we are processing..");
                $.ajax({
                    url: "{{route('add.towishlist')}}",
                    dataType: "json",
                    type: "POST",
                    data: data
                }).then(function (data) {
                    $('#ModalSlide').modal('hide');
                    Swal.fire('Added to wishlist');

                }).fail(function (error) {

                    $(".submit_wishlist").attr('disabled', false);
                    $(".submit_wishlist").text("Submit");
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
        $("body").on("click", "#wishlist_form #digital_purchase", function (e) {
            if(!$(this).is(':checked')){
                $("#wishlist_form #shipping_cost").removeAttr('disabled');
                $("#wishlist_form #expedited_shipping_fee").removeAttr('disabled');
            }else{
                $("#wishlist_form #shipping_cost").prop('disabled',true);
                $("#wishlist_form #expedited_shipping_fee").prop('disabled',true);
                $("#wishlist_form #shipping_cost").val(0);
                $("#wishlist_form #expedited_shipping_fee").val(0);
            }
        });

        $(document).on('click', '.add-to-wishlist', function (e) {
            e.preventDefault();
            $("#wishlist_form")[0].reset();
            $('.validation_display').html('');
            price = $(this).attr('data-price');
            gift_idea = $(this).attr('data-attr');
            $("#wishlist_form #url").val($(this).attr('data-url'));
            $("#wishlist_form #gift_name").val($(this).attr('data-name'));
            $("#wishlist_form #price").val($(this).attr('data-price'));
            $("#wishlist_form #shipping_cost").val($(this).attr('data-shipping'));
            $("#wishlist_form #expedited_shipping_fee").val($(this).attr('data-expedited-shipping'));
            $("#wishlist_form #quantity").val(1);
            $("#wishlist_form #type").val("gift_idea");
            $("#wishlist_form #merchant").val($(this).attr('data-merchant'));
            $("#wishlist_form #picture").val($(this).attr('data-picture'));
            $("#ModalSlide select").removeClass('.is-invalid');
            $("#ModalSlide input").removeClass('.is-invalid');
            $("#wishlist_form #shipping_cost").removeAttr('disabled');
            $("#wishlist_form #expedited_shipping_fee").removeAttr('disabled');
            $('#ModalSlide').modal('show');
        });

        $("#wishlist_form").validate({
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
            }
        });
        jQuery.validator.addMethod("acceptDonation", function (value, element) {
            var checkedNum = $('input[name="accept_donation"]:checked');
            var total_price = parseInt($('#quantity').val()) * price;
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
                return false
            } else {
                return true;
            }

        }, "Expedited shipping price must be greater than shipping cost");



    </script>


@endsection
