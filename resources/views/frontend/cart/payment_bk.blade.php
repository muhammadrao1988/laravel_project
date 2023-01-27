@extends('layouts.layoutfront')
@section('title', 'Cart')
@section('content')
<section class="shopping-cart-sec">
    <div class="container" id="container">
      <div class="row">
        <div class="col-md-6 col-sm-6 col-12">
          <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item first-nav-item">
              <a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab" id="cust-info">CUSTOMER INFO</a>
            </li>
            <!-- <li class="nav-item">
              <a class="nav-link" data-toggle="tab" href="#tabs-2" role="tab">SHIPPING INFO</a>
              </li> -->
            <li class="nav-item second-nav-item">
              <a class="nav-link" data-toggle="tab" href="#tabs-2" role="tab" id="payment-select">PAYMENT SELECTION</a>
            </li>
          </ul>
          <!-- Tab panes -->
          <div class="tab-content">
            <div class="tab-pane active" id="tabs-1" role="tabpanel">
              <h6 class="customer-info-text">Customer Information</h6>
            <form class="customer-info">
              <div class="row">
                <div class="col-md-6 col-sm-6 col-12">
                  <div class="tabs-field">
                    <label class="customer-tabs-label">Name</label>
                    <input type="text" class="customer-tabs-fields" name="name" id="name">
                    <div class="invalid-feedback validation_display"></div>
                  </div>
                </div>
                <div class="col-md-6 col-sm-6 col-12">
                  <div class="tabs-field">
                    <label class="customer-tabs-label">Email</label>
                    <input type="email" class="customer-tabs-fields" name="email" id="email">
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-12 col-sm-12 col-12">
                  <div class="tabs-field">
                    <label class="customer-tabs-label">Billing Address</label>
                    <input type="text" class="customer-tabs-fields" name="address" id="address">
                  </div>
                </div>
              </div>
            </form>
              <div class="row shopping-row">
                <div class="col-md-6 col-sm-6 col-12">
                  <a href="javascriptvoid:(0)" class="return-cart"><i class="fa fa-arrow-left back-arrow" aria-hidden="true"></i>Return to Cart</a>
                </div>
                <div class="col-md-6 col-sm-6 col-12">
                  <a href="javascriptvoid:(0)" class="pull-right continue-shopping">CONTINUE TO PAYMENT</a>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tabs-2" role="tabpanel">
              <h6 class="customer-info-text">Payment Selection</h6>
                <form action="{{route('stripe-pay')}}" id="payment" method="POST">
                    @csrf
                    <p class="accept-donation">
                        <input id="surprise" name="surprise" value="1"
                               type="checkbox"
                               class="accept-donation-check">Keep your Prezziez a surprise.
                    </p>
                    <div class="row del-row">
                        <div class="col-md-12 col-sm-12 col-12">
                        <div class="package-box">
                            <input class="Form-input" id="radio3" name="radio" type="radio" checked="checked" value="stripe">
                            <label class="Form-label  Form-label--radio new-radio-img" for="radio3">
                            <div class="radio-img">
                                <div class="radio-img-content">
                                <h6 class="standard-del">Stripe <a href="javascriptvoid:(0)" class="pull-right card-alignment"><img src="images/scard1.png" alt="image" class="img-fluid"><img src="images/scard2.png" alt="image" class="img-fluid"><img src="images/scard3.png" alt="image" class="img-fluid"></a></h6>
                                <p class="standard-del-text">Safe money transfer using your bank account. Visa, Maestro, Discover, American Express.</p>
                                </div>
                            </div>
                            </label>
                        </div>
                        </div>
                        <div class="col-md-12 col-sm-12 col-12">
                        <div class="package-box">
                            <input class="Form-input" id="radio4" name="radio" type="radio" value="google_pay">
                            <label class="Form-label  Form-label--radio new-radio-img" for="radio4">
                            <div class="radio-img">
                                <div class="radio-img-content">
                                <h6 class="standard-del">Google Pay <a href="javascriptvoid:(0)" class="pull-right card-alignment"><img src="images/paypal-logo.png" alt="image" class="img-fluid"></a></h6>
                                <p class="standard-del-text">You will be redirected to PayPal website to complete your purchase securely.</p>
                                </div>
                            </div>
                            </label>
                        </div>
                        </div>
                    </div>
                <div class="row shopping-row">
                    <div class="col-md-6 col-sm-6 col-12">
                      <a href="javascriptvoid:(0)" class="return-cart"><i class="fa fa-arrow-left back-arrow"  aria-hidden="true"></i> Back to Shipping Info</a>
                    </div>
                    <div class="col-md-6 col-sm-6 col-12">
                        {{-- <a href="javascriptvoid:(0)" class="pull-right continue-shopping">Complete Order</a> --}}
                        <button type="submit" class="pull-right  complete-order">Complete Order</button>
                </div>
              </div>
            </form>
            </div>
          </div>
        </div>
        <div class="col-md-6 col-sm-6 col-12">
          <div class="cart-rightside-box">
            <h5 class="shopping-amount">Shopping Cart <span class="shopping-quantity">{{count((array) session('cart'))}}</span></h5>
            @php $total = 0 @endphp
            @foreach (session('cart') as $id => $details)
            @php $total += $details['amount'] * $details['quantity'] @endphp
            <div class="qty-box">
              <img src="{{ asset("storage/uploads/wishlist_item/".$details['picture'])}}" alt="image" class="img-fluid">
              <div class="qty-box-main">
                <h5 class="qty-box-heading"><span class="qty-box-price">${{ $details['amount'] * $details['quantity'] }}</span>
                    {{$details['name']}}</h5>
                <p class="radio-img-heading"><b>Quantity:{{$details['quantity']}}</b></p>
              </div>
            </div>
            @endforeach
            <span class="divider-line"></span>
            <h6 class="summary-box-heading">Order Summary</h6>
            <ul>
              <li class="summary-box-list">Subtotal. ( 6 Items) <span class="pull-right summary-box-text"><strong>${{$total}}</strong></span></li>
              <li class="summary-box-list">Estimated taxes and fees <i class="fa fa-info-circle info-icon" aria-hidden="true"></i> <span class="pull-right summary-box-text"><strong>{{count((array) session('cart')) > 0 ? '$20' : ''}}</strong></span></li>
              <li class="summary-box-list">Shipping fee <span class="pull-right summary-box-text"><strong>{{count((array) session('cart')) > 0 ? '$7' : ''}}</strong></span></li>
              <li class="summary-box-list">Total: <span class="pull-right summary-box-text-color"> ${{$total}}</span></li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection
@section('scripts')
<script src="https://pay.google.com/gp/p/js/pay.js" ></script>
<script>
    $(function(){

       $('#payment-select').addClass('disabled');
        if("{{auth('web')->user()}}"){
            $('.first-nav-item').hide();
            $('.nav-link').removeClass("active");
            $('#payment-select').addClass('active');
            $('#tabs-1').removeClass("active");
            $('#tabs-2').addClass("active");
        }

      $('.complete-order').click(function(e){
        e.preventDefault();
         var val = $('input[name=radio]:checked', '#payment').val()
        if(val == 'google_pay'){
          const paymentsClient  = new google.payments.api.PaymentsClient({
                  environment:'TEST'
              })
        // Step1
            const baseRequest = {
                apiVersion: 2,
                apiVersionMinor: 0
            };

            // Step 2
            const tokenizationSpecification = {
                type: 'PAYMENT_GATEWAY',
                parameters: {
                'gateway': 'example',
                'gatewayMerchantId': 'exampleGatewayMerchantId'
                }
            };

            // Step 3
            const allowedCardNetworks = ["AMEX", "DISCOVER", "INTERAC", "JCB", "MASTERCARD", "MIR", "VISA"];
            const allowedCardAuthMethods = ["PAN_ONLY", "CRYPTOGRAM_3DS"];

            // Step 4
            const baseCardPaymentMethod = {
                 type: 'CARD',
                 parameters: {
                 allowedAuthMethods: allowedCardAuthMethods,
                 allowedCardNetworks: allowedCardNetworks
                 }
            };
           const cardPaymentMethod = Object.assign(
              {tokenizationSpecification: tokenizationSpecification},
              baseCardPaymentMethod
           );
            const isReadyToPayRequest = Object.assign({}, baseRequest);
            isReadyToPayRequest.allowedPaymentMethods = [baseCardPaymentMethod];

             paymentsClient.isReadyToPay(isReadyToPayRequest)
                .then(function(response) {
                if (response.result) {
                    // add a Google Pay payment button
                }
                })
                .catch(function(err) {
                // show error in developer console for debugging
                console.error(err,"response_error");
            });



            // Step 8
        const paymentDataRequest = Object.assign({}, baseRequest);

            paymentDataRequest.allowedPaymentMethods = [cardPaymentMethod];

            paymentDataRequest.transactionInfo = {
                totalPriceStatus: 'FINAL',
                totalPrice: '123.45',
                currencyCode: 'USD',
                countryCode: 'US'
            };

            paymentDataRequest.merchantInfo = {
                merchantName: 'Abcded',
                merchantId: '12345678901234589034'
            };

			console.log(paymentDataRequest)
            // Step 9
            paymentsClient.loadPaymentData(paymentDataRequest).then(function(paymentData){
              console.log(paymentData)
                // if using gateway tokenization, pass this token without modification
                paymentToken = paymentData.paymentMethodData.tokenizationData.token;
                }).catch(function(err){
                // show error in developer console for debugging
                console.error(err,"error");
            });
        }else{
          $('#payment').submit();
        }
      })

    });

    $('.continue-shopping').click(function(){
        if($(".customer-info").valid()){
            $('.nav-link').removeClass("active");
            $('.tab-pane').removeClass("active");
            $('#payment-select').addClass("active");
            $('#tabs-2').addClass("active");
            const billing_address = {
                name : $('#name').val(),
                email : $('#email').val(),
                address : $('#address').val(),
            }
            $.ajax({
                url: '{{ route('billing-address') }}',
                method: "post",
                data: {
                    _token: '{{ csrf_token() }}',
                    billing_address : billing_address
                },
            })
        }
    });
            jQuery.validator.addMethod("email", function (value, element) {
                var validRegex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                if (value.match(validRegex)) {
                    return true;
                }
                return false;
            }, "Invalid Email");

            $(".customer-info").validate({
                errorClass: 'is-invalid',
                errorElement: "div",
                errorPlacement: function (error, element) {
                    error.insertAfter(element.closest('div'));
                    error.addClass("validation_display display-error");
                },
                rules: {
                    name :{
                        required:1,
                        minlength: 4,
                    },
                    email:{
                        required:1
                    },
                    address:{
                        required:1
                    }
                },
            });


</script>
@endsection
