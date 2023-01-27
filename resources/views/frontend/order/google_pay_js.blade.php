<script>
    $(".google_checkout_validate").click(function () {
       google_pay_process(true,"");
    });
    function google_pay_process(isRead,paymentToken){
        var data2 = $('#billing_info_form').serialize();

        if($("#gift_offer_note").length > 0){
            data2 += "&gift_offer_note="+$("#gift_offer_note").val();
        }

        if(isRead==true){
            data2 += "&isRead=1";
        }
        if(paymentToken!=""){
            data2 += "&transaction_id="+paymentToken;
        }
        data2 += "&isGooglePay=1";
        $("#complete_order").attr('disabled', true);
        $(".google_checkout_validate").attr('disabled', true);
        $(".loading").show();
        $.ajax({
            url: "{{route('payment')}}",
            dataType: "json",
            type: "POST",
            data: data2
        }).then(function (data) {
            $(".loading").hide();
            if(isRead==true){
                console.log("ttt");
                $("body #google_pay_button button").trigger("click");
                $("#complete_order").removeAttr('disabled');
                $(".google_checkout_validate").removeAttr('disabled');
            }else {
                toastr.success(data.msg, "Success");
                setTimeout(function () {
                    location.href = "{{route('success')}}";

                }, 1000);
            }

        }).fail(function (error) {
            $(".loading").hide();
            $("#complete_order").removeAttr('disabled');
            $(".google_checkout_validate").removeAttr('disabled');
            //$("#result").text(error.responseJSON.message);
            if (error.responseJSON.hasOwnProperty('errors')) {
                var error_msg = "";

                for (var prop in error.responseJSON.errors) {

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
    }

    function myFunction() {
        console.log($("body #container_btn button"));
        $("body #container_btn button").trigger("click");
    }
    /**
     * Define the version of the Google Pay API referenced when creating your
     * configuration
     *
     * @see {@link https://developers.google.com/pay/api/web/reference/request-objects#PaymentDataRequest|apiVersion in PaymentDataRequest}
     */
    const baseRequest = {
        apiVersion: 2,
        apiVersionMinor: 0
    };

    /**
     * Card networks supported by your site and your gateway
     *
     * @see {@link https://developers.google.com/pay/api/web/reference/request-objects#CardParameters|CardParameters}
     * @todo confirm card networks supported by your site and gateway
     */
    const allowedCardNetworks = ["AMEX", "DISCOVER", "JCB", "MASTERCARD", "MIR", "VISA"];

    /**
     * Card authentication methods supported by your site and your gateway
     *
     * @see {@link https://developers.google.com/pay/api/web/reference/request-objects#CardParameters|CardParameters}
     * @todo confirm your processor supports Android device tokens for your
     * supported card networks
     */
    const allowedCardAuthMethods = ["PAN_ONLY", "CRYPTOGRAM_3DS"];

    /**
     * Identify your gateway and your site's gateway merchant identifier
     *
     * The Google Pay API response will return an encrypted payment method capable
     * of being charged by a supported gateway after patyer authorization
     *
     * @todo check with your gateway on the parameters to pass
     * @see {@link https://developers.google.com/pay/api/web/reference/request-objects#gateway|PaymentMethodTokenizationSpecification}
     */
    const tokenizationSpecification = {
        type: 'PAYMENT_GATEWAY',
        parameters: {
            'gateway': 'example',
            'gatewayMerchantId': 'BCR2DN4T7TSMJRBM'
        }
    };

    /**
     * Describe your site's support for the CARD payment method and its required
     * fields
     *
     * @see {@link https://developers.google.com/pay/api/web/reference/request-objects#CardParameters|CardParameters}
     */
    const baseCardPaymentMethod = {
        type: 'CARD',
        parameters: {
            allowedAuthMethods: allowedCardAuthMethods,
            allowedCardNetworks: allowedCardNetworks
        }
    };

    /**
     * Describe your site's support for the CARD payment method including optional
     * fields
     *
     * @see {@link https://developers.google.com/pay/api/web/reference/request-objects#CardParameters|CardParameters}
     */
    const cardPaymentMethod = Object.assign(
        {},
        baseCardPaymentMethod,
        {
            tokenizationSpecification: tokenizationSpecification
        }
    );

    /**
     * An initialized google.payments.api.PaymentsClient object or null if not yet set
     *
     * @see {@link getGooglePaymentsClient}
     */
    let paymentsClient = null;

    /**
     * Configure your site's support for payment methods supported by the Google Pay
     * API.
     *
     * Each member of allowedPaymentMethods should contain only the required fields,
     * allowing reuse of this base request when determining a viewer's ability
     * to pay and later requesting a supported payment method
     *
     * @returns {object} Google Pay API version, payment methods supported by the site
     */
    function getGoogleIsReadyToPayRequest() {
        return Object.assign(
            {},
            baseRequest,
            {
                allowedPaymentMethods: [baseCardPaymentMethod]
            }
        );
    }

    /**
     * Configure support for the Google Pay API
     *
     * @see {@link https://developers.google.com/pay/api/web/reference/request-objects#PaymentDataRequest|PaymentDataRequest}
     * @returns {object} PaymentDataRequest fields
     */
    function getGooglePaymentDataRequest() {
        const paymentDataRequest = Object.assign({}, baseRequest);
        paymentDataRequest.allowedPaymentMethods = [cardPaymentMethod];
        paymentDataRequest.transactionInfo = getGoogleTransactionInfo();
        paymentDataRequest.merchantInfo = {
            // @todo a merchant ID is available for a production environment after approval by Google
            // See {@link https://developers.google.com/pay/api/web/guides/test-and-deploy/integration-checklist|Integration checklist}
            merchantId: 'BCR2DN4T7TSMJRBM',
            merchantName: 'PREZZIEZ LLC'
        };

        paymentDataRequest.callbackIntents = [ "PAYMENT_AUTHORIZATION"];
        /*  paymentDataRequest.shippingAddressRequired = true;
          paymentDataRequest.shippingAddressParameters = getGoogleShippingAddressParameters();
          paymentDataRequest.shippingOptionRequired = true;*/

        return paymentDataRequest;
    }

    /**
     * Return an active PaymentsClient or initialize
     *
     * @see {@link https://developers.google.com/pay/api/web/reference/client#PaymentsClient|PaymentsClient constructor}
     * @returns {google.payments.api.PaymentsClient} Google Pay API client
     */
    function getGooglePaymentsClient() {
        if ( paymentsClient === null ) {
            paymentsClient = new google.payments.api.PaymentsClient({
                environment: "TEST",
                merchantInfo: {
                    merchantName: "PREZZIEZ LLC",
                    merchantId: "BCR2DN4T7TSMJRBM"
                },
                paymentDataCallbacks: {
                    onPaymentAuthorized: onPaymentAuthorized,
                    //onPaymentDataChanged: onPaymentDataChanged
                }
            });
        }
        return paymentsClient;
    }


    function onPaymentAuthorized(paymentData) {
        return new Promise(function(resolve, reject){

            // handle the response
            processPayment(paymentData)
                .then(function() {
                    console.log("ff");
                    resolve({transactionState: 'SUCCESS'});
                })
                .catch(function() {
                    console.log("error 33");
                    resolve({
                        transactionState: 'ERROR',
                        error: {
                            intent: 'PAYMENT_AUTHORIZATION',
                            message: 'Insufficient funds',
                            reason: 'PAYMENT_DATA_INVALID'
                        }
                    });
                });

        });
    }

    /**
     * Handles dynamic buy flow shipping address and shipping options callback intents.
     *
     * @param {object} itermediatePaymentData response from Google Pay API a shipping address or shipping option is selected in the payment sheet.
     * @see {@link https://developers.google.com/pay/api/web/reference/response-objects#IntermediatePaymentData|IntermediatePaymentData object reference}
     *
     * @see {@link https://developers.google.com/pay/api/web/reference/response-objects#PaymentDataRequestUpdate|PaymentDataRequestUpdate}
     * @returns Promise<{object}> Promise of PaymentDataRequestUpdate object to update the payment sheet.
     */
    function onPaymentDataChanged(intermediatePaymentData) {
        return new Promise(function(resolve, reject) {

            let shippingAddress = intermediatePaymentData.shippingAddress;
            let shippingOptionData = intermediatePaymentData.shippingOptionData;
            let paymentDataRequestUpdate = {};

            if (intermediatePaymentData.callbackTrigger == "INITIALIZE" || intermediatePaymentData.callbackTrigger == "SHIPPING_ADDRESS") {
                if(shippingAddress.administrativeArea == "NJ")  {
                    paymentDataRequestUpdate.error = getGoogleUnserviceableAddressError();
                }
                else {
                    paymentDataRequestUpdate.newShippingOptionParameters = getGoogleDefaultShippingOptions();
                    let selectedShippingOptionId = paymentDataRequestUpdate.newShippingOptionParameters.defaultSelectedOptionId;
                    paymentDataRequestUpdate.newTransactionInfo = calculateNewTransactionInfo(selectedShippingOptionId);
                }
            }
            else if (intermediatePaymentData.callbackTrigger == "SHIPPING_OPTION") {
                paymentDataRequestUpdate.newTransactionInfo = calculateNewTransactionInfo(shippingOptionData.id);
            }

            resolve(paymentDataRequestUpdate);
        });
    }

    /**
     * Helper function to create a new TransactionInfo object.

     * @param string shippingOptionId respresenting the selected shipping option in the payment sheet.
     *
     * @see {@link https://developers.google.com/pay/api/web/reference/request-objects#TransactionInfo|TransactionInfo}
     * @returns {object} transaction info, suitable for use as transactionInfo property of PaymentDataRequest
     */
    function calculateNewTransactionInfo(shippingOptionId) {
        let newTransactionInfo = getGoogleTransactionInfo();

        let shippingCost = getShippingCosts()[shippingOptionId];
        newTransactionInfo.displayItems.push({
            type: "LINE_ITEM",
            label: "Shipping cost",
            price: shippingCost,
            status: "FINAL"
        });

        let totalPrice = 0.00;
        newTransactionInfo.displayItems.forEach(displayItem => totalPrice += parseFloat(displayItem.price));
        newTransactionInfo.totalPrice = totalPrice.toString();

        return newTransactionInfo;
    }

    /**
     * Initialize Google PaymentsClient after Google-hosted JavaScript has loaded
     *
     * Display a Google Pay payment button after confirmation of the viewer's
     * ability to pay.
     */
    function onGooglePayLoaded() {
        const paymentsClient = getGooglePaymentsClient();
        paymentsClient.isReadyToPay(getGoogleIsReadyToPayRequest())
            .then(function(response) {
                if (response.result) {
                    addGooglePayButton();
                    // @todo prefetch payment data to improve performance after confirming site functionality
                    // prefetchGooglePaymentData();
                }
            })
            .catch(function(err) {
                // show error in developer console for debugging
                console.log("err 1");
                console.error(err);
            });
    }

    /**
     * Add a Google Pay purchase button alongside an existing checkout button
     *
     * @see {@link https://developers.google.com/pay/api/web/reference/request-objects#ButtonOptions|Button options}
     * @see {@link https://developers.google.com/pay/api/web/guides/brand-guidelines|Google Pay brand guidelines}
     */
    function addGooglePayButton() {
        const paymentsClient = getGooglePaymentsClient();
        const button =
            paymentsClient.createButton({
                onClick: onGooglePaymentButtonClicked,
                allowedPaymentMethods: [baseCardPaymentMethod]
            });
        document.getElementById('google_pay_button').appendChild(button);
    }

    /**
     * Provide Google Pay API with a payment amount, currency, and amount status
     *
     * @see {@link https://developers.google.com/pay/api/web/reference/request-objects#TransactionInfo|TransactionInfo}
     * @returns {object} transaction info, suitable for use as transactionInfo property of PaymentDataRequest
     */
    function getGoogleTransactionInfo() {
        var total = $(".order_actual_total_amount_gpay").val();
        return {
           /* displayItems: [
                {
                    label: "Subtotal",
                    type: "SUBTOTAL",
                    price: "11.00",
                },
                {
                    label: "Prezzeiz",
                    type: "LINE_ITEM",
                    price: "1.00",
                },
                {
                    label: "Tax",
                    type: "TAX",
                    price: "1.00",
                }
            ],*/
            countryCode: 'US',
            currencyCode: "USD",
            totalPriceStatus: "FINAL",
            totalPrice:total,
            totalPriceLabel: "Total"
        };
    }

    /**
     * Provide a key value store for shippping options.
     */
    function getShippingCosts() {
        return {
            "shipping-001": "0.00",
            "shipping-002": "1.99",
            "shipping-003": "10.00"
        }
    }

    /**
     * Provide Google Pay API with shipping address parameters when using dynamic buy flow.
     *
     * @see {@link https://developers.google.com/pay/api/web/reference/request-objects#ShippingAddressParameters|ShippingAddressParameters}
     * @returns {object} shipping address details, suitable for use as shippingAddressParameters property of PaymentDataRequest
     */
    function getGoogleShippingAddressParameters() {
        return  {
            allowedCountryCodes: ['US'],
            phoneNumberRequired: true
        };
    }

    /**
     * Provide Google Pay API with shipping options and a default selected shipping option.
     *
     * @see {@link https://developers.google.com/pay/api/web/reference/request-objects#ShippingOptionParameters|ShippingOptionParameters}
     * @returns {object} shipping option parameters, suitable for use as shippingOptionParameters property of PaymentDataRequest
     */
    function getGoogleDefaultShippingOptions() {
        return {
            defaultSelectedOptionId: "shipping-001",
            shippingOptions: [
                {
                    "id": "shipping-001",
                    "label": "Free: Standard shipping",
                    "description": "Free Shipping delivered in 5 business days."
                },
                {
                    "id": "shipping-002",
                    "label": "$1.99: Standard shipping",
                    "description": "Standard shipping delivered in 3 business days."
                },
                {
                    "id": "shipping-003",
                    "label": "$10: Express shipping",
                    "description": "Express shipping delivered in 1 business day."
                },
            ]
        };
    }

    /**
     * Provide Google Pay API with a payment data error.
     *
     * @see {@link https://developers.google.com/pay/api/web/reference/response-objects#PaymentDataError|PaymentDataError}
     * @returns {object} payment data error, suitable for use as error property of PaymentDataRequestUpdate
     */
    function getGoogleUnserviceableAddressError() {
        return {
            reason: "SHIPPING_ADDRESS_UNSERVICEABLE",
            message: "Cannot ship to the selected address",
            intent: "SHIPPING_ADDRESS"
        };
    }

    /**
     * Prefetch payment data to improve performance
     *
     * @see {@link https://developers.google.com/pay/api/web/reference/client#prefetchPaymentData|prefetchPaymentData()}
     */
    function prefetchGooglePaymentData() {
        const paymentDataRequest = getGooglePaymentDataRequest();
        // transactionInfo must be set but does not affect cache
        paymentDataRequest.transactionInfo = {
            totalPriceStatus: 'NOT_CURRENTLY_KNOWN',
            currencyCode: 'USD'
        };
        const paymentsClient = getGooglePaymentsClient();
        paymentsClient.prefetchPaymentData(paymentDataRequest);
    }


    /**
     * Show Google Pay payment sheet when Google Pay payment button is clicked
     */
    function onGooglePaymentButtonClicked() {
        const paymentDataRequest = getGooglePaymentDataRequest();
        paymentDataRequest.transactionInfo = getGoogleTransactionInfo();

        const paymentsClient = getGooglePaymentsClient();
        try {
            paymentsClient.loadPaymentData(paymentDataRequest);
        }catch (e) {
            console.log("dddeww");
        }
    }

    /**
     * Process payment data returned by the Google Pay API
     *
     * @param {object} paymentData response from Google Pay API after user approves payment
     * @see {@link https://developers.google.com/pay/api/web/reference/response-objects#PaymentData|PaymentData object reference}
     */
    function processPayment(paymentData) {
        return new Promise(function(resolve, reject) {
            setTimeout(function() {
                // show returned data in developer console for debugging
                //console.log("success");
                //console.log(paymentData);
                // @todo pass payment token to your gateway to process payment
                paymentToken = paymentData.paymentMethodData.tokenizationData.token;
                google_pay_process(false,paymentToken);

                resolve({});
            }, 3000);
        });
    }</script>