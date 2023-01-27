<h6 class="customer-info-text">Payment Selection</h6>
<div class="col-md-12 col-sm-12 col-12 mb-3 text-right">
    <a href="javascript:;" class="return-billing-info"><i class="fa fa-arrow-left back-arrow" aria-hidden="true"></i> Back to Billing Info</a>
</div>

<div class="row del-row order_card_process" style="display: none">
    <form id="payment_form" method="POST">
        @csrf

        <div class="col-md-12 col-sm-12 col-12">
            <div class="package-box">
                <!-- <input class="Form-input" id="radio3" name="payment_type" value="car" type="radio" checked="checked"> -->
                <div class="radiobuttons">
                    <div class="rdio rdio-primary radio-inline"> <input name="radio" value="1" id="radio1" type="radio" checked>
                      <label for="radio1"></label>
                    </div>
                   
                </div>
                <label class="Form-label  Form-label--radio new-radio-img" for="radio3">
                    <div class="radio-img">
                        <div class="radio-img-content">
                            <h6 class="standard-del">Credit Card <a href="javascript:;" class="pull-right card-alignment">
                                    <img src="{{asset('image/web/scard1.png')}}" alt="image" class="img-fluid">
                                    <img src="{{asset('image/web/scard2.png')}}" alt="image" class="img-fluid">
                                    <img src="{{asset('image/web/scard3.png')}}" alt="image" class="img-fluid">
                                </a></h6>
                            <p class="standard-del-text">Safe money transfer using your bank account. Visa, Maestro, Discover, American Express.</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-12">
                            <div class="card-no-text">
                                <label class="card-content">CARD NUMBER</label>
                                <div class="card-img-right">

                                    <input data-name="card number" autocomplete="off" required placeholder="---- ---- ---- ----" type="tel"
                                           size="19" type="text" class="card-no-field" name="card_number" value=""
                                           id="ccnum">
                                    <label class="input-payform-error-ccnum" style="color: #dc3545"></label>


                                    <div class="card-img-right-img">
                                        <img src="{{asset('image/web/line.png')}}" alt="image" class="img-fluid">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row visa-card-entry">
                        <div class="col-md-6 col-sm-6 col-12 name-card">
                            <div class="card-no-text">
                                <label class="card-content">NAME ON CARD</label>
                                <input data-name="name on card" autocomplete="off" type="text" class="card-no-field" id="name_on_card"
                                       name="name_on_card" placeholder="" required="">
                                <label class="input-payform-error-name_on_card" style="color: #dc3545"></label>
                                <input type="hidden" id="ccnum-type">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-12">
                            <div class="row visa-card-inner">
                                <div class="col-md-6 col-sm-6 col-12">
                                    <div class="card-no-text">
                                        <label class="card-content">EXPIRY DATE</label>
                                        <input required data-name="expire date" class="card-no-field" autocomplete="off"
                                               placeholder="MM / YY" size=5" type="tel" name="card_expiry" value=""
                                               id="expiry">
                                        <label class="input-payform-error-expiry" style="color: #dc3545"></label>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-12">
                                    <div class="card-no-text">
                                        <label class="card-content">CVV CODE</label>
                                        <div class="cvv-code-text">
                                            <input data-name="CVV"  autocomplete="off" required class="card-no-field cvv-card-field" placeholder="---" size="4"
                                                   type="tel" name="cvc" value="" id="cvc">
                                            <label class="input-payform-error-cvc" style="color: #dc3545"></label>
                                            <div class="cvv-img">
                                                <img src="{{asset('image/web/ques.png')}}" alt="image" class="img-fluid">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </label>
            </div>
        </div>
    </form>
</div>
<div class="row order_empty_process">
    <div class="col-md-12 mt-5">
        <p>Please click on Complete Order button to proceed your order.</p>
        <input type="hidden" name="direct_checkout" value="1">
    </div>
</div>
<div class="row shopping-row">

    <div class="col-md-5 col-12">
        <a href="javascript:;" class="pull-right continue-shopping" id="complete_order">Complete Order</a>
    </div>
    <div class="col-md-1 col-12 order_card_process or-cls" style="display: none">
        OR 
    </div>

    <div class="col-md-5 col-12 order_card_process" style="display: none">

        <button class="continue-shopping google_checkout_validate" style="background: black; color: #fff">Buy with <i class="fa fa-google"></i> Pay </button>

        <p style="display: none;" class="mt-4" id="google_pay_button"></p>
    </div>

</div>
