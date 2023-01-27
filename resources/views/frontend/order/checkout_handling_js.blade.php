<script src="{{ asset('js/payform.min.js') }}"></script>
<script>
    (function () {
        @if($auth_user_web)
            setTimeout(function () {
            if($("#billing_info_form").valid()){
                $('#payment-select').removeClass('disabled');
            }
        },1000)

        @endif

        $('.continue_payment_button').click(function(){
            if($("#billing_info_form").valid()){
                $('#payment-select').removeClass('disabled');
                $("#payment-select").trigger("click");
            }else{
                $('#payment-select').addClass('disabled');
            }
        });

        $('.return-billing-info').click(function(){
            $("#billing-select").trigger("click");
        });

        var actual_total = parseFloat($("#order_actual_total").val());
        if(actual_total > 0){
            $(".order_empty_process").remove();
            $(".order_card_process").show();
            var ccnum = document.getElementById('ccnum'),
                type = document.getElementById('ccnum-type'),
                name_on_card = document.getElementById('name_on_card'),
                expiry = document.getElementById('expiry'),
                cvc = document.getElementById('cvc');


            payform.cardNumberInput(ccnum);
            payform.expiryInput(expiry);
            payform.cvcInput(cvc);

            ccnum.addEventListener('input', updateType);
        }else{
            $(".order_card_process").remove();
            $(".order_empty_process").show();
        }
        var submit = document.getElementById('complete_order');

        submit.addEventListener('click', function () {
            var valid = [];
            if(actual_total > 0) {
                var expiryObj = payform.parseCardExpiry(expiry.value);
                var name_on_card_validate = true;

                if (name_on_card.value == "") {
                    name_on_card_validate = false;
                }


                valid.push(fieldStatus(name_on_card, name_on_card_validate));
                valid.push(fieldStatus(ccnum, payform.validateCardNumber(ccnum.value)));
                valid.push(fieldStatus(expiry, payform.validateCardExpiry(expiryObj)));
                valid.push(fieldStatus(cvc, payform.validateCardCVC(cvc.value, type.innerHTML)));
            }

            if (valid.every(Boolean) && $("#billing_info_form").valid()) {
                $(".loading").show();
                //var data = $('#complete_order').serialize();
                var data2 = $('#billing_info_form').serialize();
                var gift_offer_note = "";
                if($("#gift_offer_note").length > 0){
                    gift_offer_note = "&gift_offer_note="+$("#gift_offer_note").val();
                }
                if(actual_total > 0){
                    data2 +="&name_on_card="+name_on_card.value+"&card_number="+ccnum.value+"&card_expiry="+expiry.value+"&cvc="+cvc.value;
                }else{
                    data2 +="&direct_checkout=1";
                }
                data2 += gift_offer_note;
                //console.log(data2);
                //return false;
                $("#complete_order").attr('disabled', true);

                //$("#result").text("Please wait, we are processing..");
                $.ajax({
                    url: "{{route('payment')}}",
                    dataType: "json",
                    type: "POST",
                    data: data2
                }).then(function (data) {
                    $(".loading").hide();
                    toastr.success(data.msg, "Success");
                    setTimeout(function () {
                        location.href = "{{route('success')}}";

                    }, 1000);

                }).fail(function (error) {
                    $(".loading").hide();
                    $("#complete_order").removeAttr('disabled');
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


            //result.className = 'emoji ' + (valid.every(Boolean) ? 'valid' : 'invalid');
        });

        function updateType(e) {
            var cardType = payform.parseCardType(e.target.value);
            //type.innerHTML = cardType || 'invalid';
        }


        function fieldStatus(input, valid) {
            var id = input.getAttribute('id');
            if (valid) {
                removeClass(input, 'error-input');
                $(".input-payform-error-"+id).text("");
            } else {
                addClass(input, 'error-input');
                setTimeout(function () {
                    console.log(id,$("#"+id).hasClass('is-invalid'));
                    if(!$("#"+id).hasClass('is-invalid')){
                        var fieldName= $("#"+id).attr('data-name');
                        $(".input-payform-error-"+id).text("Invalid "+fieldName);
                    }else{
                        $(".input-payform-error-"+id).text("");
                    }
                },500)


            }
            return valid;
        }

        function addClass(ele, _class) {
            if (ele.className.indexOf(_class) === -1) {
                ele.className += ' ' + _class;
            }
        }

        function removeClass(ele, _class) {
            if (ele.className.indexOf(_class) !== -1) {
                ele.className = ele.className.replace(_class, '');
            }
        }
    })();
    $("#billing_info_form").validate({
        errorClass: 'is-invalid',
        errorElement: "div",
        errorPlacement: function (error, element) {
            error.insertAfter(element.closest('div'));
            error.addClass("validation_display display-error");
        },
        invalidHandler: function() {
            $(this).find(":input.is-invalid:first").focus();
        },

    });


</script>