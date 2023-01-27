<div class="modal modal2 fade" id="wishlist_item_add_edit_modal" tabindex="-1" role="dialog" aria-labelledby="wishlist_item_add_edit_modal"
     aria-hidden="true">
    <div class="modal-dialog modal-dialog-slideout ">
        <div class="modal-content">
            <form method="post" enctype="multipart/form-data" action="#" name="eventForm" id="eventForm"
                  class="eventForm">
                {{csrf_field()}}
                <input type="hidden" name="type" value="" id="type">
                <input type="hidden" name="id" value="" id="id">
                <input type="hidden" name="to_user" value="" id="to_user">
                <input type="hidden" name="giftee_wishlist_id" value="{{!empty($id) ? $id :""}}" id="giftee_wishlist_id">
                <div class="modal-body">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <div class="row">
                        <div class="col-md-4 col-sm-4 col-12 fade-color">
                            <div class="containers">
                                <div class="imageWrapper">
                                    <img class="image" id="picture" src="{{asset('image/web/upload-pre.png')}}" alt="image">
                                </div>
                            </div>
                            <div class="info gift-text" style="font-size: 12px">Note: The recommended size is 425 x 330 pixels</div>
                            <div data-name="picture" class=" error_upload_picture_wishlist text-center"></div>
                            <button class="file-upload">
                                <input accept="image/*" name="picture" type="file" id="file_input" class="file-input">UPLOAD A
                                PICTURE
                            </button>
                        </div>
                        <div class="col-md-8 col-sm-8 col-12 size-set">
                            <div class="modal-scroller">
                            <div class="scrollbar" id="style-3">
                            <div class="force-overflow">
                            <h4 class="confirm-text-heading">Confirm Details</h4>
                            <div class="row">
                                <div class="col-md-12 url_main_manual">
                                    <div class="confirm-form">
                                        <label class="gift-text">URL *</label>
                                        <input required  id="url" name="url" class="gift-field" type="text"
                                               placeholder="Enter URL">
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
                                        <div class="price-boxx">
                                        <input id="price" required name="price" class="gift-field hide_counter" type="number"
                                               data-name="price" data-validation-name="price" placeholder="ex. $1000">
                                        <i data-toggle="tooltip"
                                           data-placement="top"
                                           title="We recommend putting the full price of the item instead of temporary sale prices"
                                           class="fa fa-info-circle info-icon-setting accept-donation"
                                           style="cursor: pointer"
                                           tabindex="-1"
                                           aria-hidden="true">

                                        </i>
                                        </div>
                                        <div class="invalid-feedback validation_display"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-12">
                                    <div class="confirm-form">
                                        <label class="gift-text">Shipping Cost:</label>
                                        <input id="shipping_cost" required name="shipping_cost" class="gift-field hide_counter"
                                               data-name="shipping cost" data-validation-name="shipping cost" type="number" placeholder="ex. $20">
                                        <div class="invalid-feedback validation_display"></div>

                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-12 accept_main_field">
                                    <div class="confirm-form">
                                        <label class="gift-text">Expedited Shipping:</label>
                                        <input id="expedited_shipping_fee"   name="expedited_shipping_fee" class="gift-field hide_counter"
                                               data-name="Expedited shipping" data-validation-name="Expedited shipping" type="number" placeholder="ex. $25">
                                        <div class="invalid-feedback validation_display"></div>

                                    </div>
                                </div>

                            </div>
                            <div class="row">
                                <div class="col-md-6 col-sm-6 col-12">
                                    <div class="confirm-form">
                                        <label class="gift-text">Quantity:</label>
                                        <input id="quantity" required name="quantity" class="gift-field"
                                               data-name="quantity" data-validation-name="quantity" type="number" min="1" placeholder="Enter Quantity">
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
                                            <i data-toggle="tooltip" data-placement="top" title="Choose if you’d like to give multiple gifters the opportunity to chip in towards the total cost" class="fa fa-info-circle" style="cursor: pointer" tabindex="-1" aria-hidden="true"></i>
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
                                                <i data-toggle="tooltip"
                                                   data-placement="top"
                                                   title="Digital purchase are those things which are non-physical e.g. e-books,subscription, vouchers etc"
                                                   class="fa fa-info-circle"
                                                   style="cursor: pointer"
                                                   tabindex="-1"
                                                   aria-hidden="true">

                                                </i>

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
                            <div class="donation-sec">
                                <a href="javascript:;" class="donation-buttn save_wishlist_item">SAVE</a>
                            </div>
                            </div>
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
