@extends('layouts.layoutfront')
@section('title', 'Cart')
@section('content')
    <!-- CART 1 SECTION BEGIN -->
    <!-- CART 1 SECTION END -->
    <section class="cards-selection-sec cart_section">
        <div class="container">
          <div class="row">
            <div class="col-md-7 col-sm-12 col-12 cart_item_main">
              <form class=Form>
                <div class="l-container">
                  {{-- <input class="Form-input" id="radio1" name=radio type="radio"> --}}
                  <input type="checkbox" class="Form-input checkAll" id="radio1">
                  <label class="Form-label  Form-label--radio new-radio-label" for="radio1">

                      SELECT ALL ({{$cart_count}} ITEM{{$cart_count > 1 ? "S" : "" }})
                      <a href="javascriptvoid:(0)" class="pull-right light-clr" id="delete-selected"><i class="fa fa-trash light-clr-trash" aria-hidden="true" ></i> Delete</a></label>
                      @php
                          $total = 0;
                          $shipping_fee = 0;
                          $expedited_shipping_fee = 0;
                          $sub_total = 0;
                          $user_id_giftee = 0;
                      @endphp
                    @foreach (session('cart') as $id => $details)
                        @php
                            $total = $total +  $details['amount'];
                            $sub_total = $sub_total +  $details['amount'];
                            $shipping_fee = $shipping_fee + $details['shipping_cost'];
                            $expedited_shipping_fee = $expedited_shipping_fee + $details['expedited_shipping_fee'];
                            $user_id_giftee = $details['user_id'];
                        @endphp
                        {{-- <input class=Form-input id="{{$id}}" name=radio type="radio"> --}}
                        <input type="checkbox" class="Form-input checkbox" id="{{$id}}" name="checkbox[]">
                    <label class="Form-label  Form-label--radio new-radio-img" for="{{$id}}"
                     data-id="{{ $id }}">
                        <a class="cross-mark pull-right"><i class="fa fa-times" aria-hidden="true"></i></a>
                        <div class="radio-img">
                            @if($details['picture']=="")
                                <img src="{{ asset('image/web/upload-pre.png')}}"  alt="image" class="img-fluid">
                            @else
                                <img src="{{ asset("storage/uploads/wishlist_item/".$details['picture'])}}"  alt="image" class="img-fluid">
                            @endif
                          <div class="radio-img-content">
                            <h6 class="radio-img-heading"><span class="radio-img-price">
                                ${{ \App\Helpers\Common::numberFormat($details['unit_price'])  }}</span> {{$details['name']}}</h6>
                            <p class="radio-img-heading">Quantity: {{$details['quantity']}}</p>
                          </div>
                        </div><br>
                        <a class="ml-4" href="{{route('show.wishlist.item',\App\Helpers\Common::encrypt_decrypt($details['wishlist_id']))}}">Back to wishlist</a>
                      </label>
                    @endforeach
                </div>
              </form>
            </div>
            <div class="col-md-5 col-sm-12 col-12">
              <div class="summary-box summary-text-area">

                @include('frontend.cart.order_summary')

                <h4 class="summary-box-heading">We Accept:</h4>
                <ul class="cards-icons">
                  <li class="cards-list"><a href="javascript:;"><img src="{{asset('image/web/visa.png')}}" alt="image" class="img-fluid"></a></li>
                  <li class="cards-list"><a href="javascript:;"><img src="{{asset('image/web/mastercard.png')}}" alt="image" class="img-fluid"></a></li>
                </ul>
              </div>
            </div>
          </div>
        </div>
    </section>

    @include('frontend.cart.estimated_info_modal')
    <!-- Delete Modal -->
    <div class="modal modal1 fade" id="deleteModalCart" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-slideout" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="delete_type" value="">
                    {{--<h5 class="modal-title add-prezziez-heading align-right" id="exampleModalLabel">Remove from Cart</h5>--}}
                        <p class="add-prezziez-text">Are you sure you want to remove this item from the cart ?</p>
                        <br>
                        <button type="button" style="margin: 0 auto" class="add-next delete_cart_item">Confirm</button>
                        <br>
                        <a href="javascript:;"  data-dismiss="modal" class="mt-2 text-center" style="display: block">Cancel</a>
                </div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>
    <div class="modal modal1 fade" id="infoModalCart" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel2"
         aria-hidden="true">
        <div class="modal-dialog modal-dialog-slideout" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h5 class="modal-title add-prezziez-heading align-right" id="exampleModalLabel">No item selected</h5>
                    <p class="add-prezziez-text">Please select at-least one item from the list</p>
                    <br>


                    <button type="button" data-dismiss="modal" style="margin: 0 auto" class="add-next">Close</button>
                </div>
                <div class="modal-footer"></div>
            </div>
        </div>
    </div>
    <!-- MODAL 1 END -->
@endsection

@section('scripts')
<script type="text/javascript">

    $(".update-cart").change(function (e) {
        e.preventDefault();
        var ele = $(this);
        $.ajax({
            url: '{{ route('update.cart') }}',
            method: "patch",
            data: {
                _token: '{{ csrf_token() }}',
                id: ele.parents("tr").attr("data-id"),
                quantity: ele.parents("tr").find(".quantity").val()
            },
            success: function (response) {
               window.location.reload();
            }
        });
    });

    $('.shopping-cart-buttn').click(function(e){
        e.preventDefault();
        var user = '<?php echo($auth_user_web) ?>';
        if(user == ""){
            $('#ModalSlide').modal('show');
        }else{
            console.log(user);
        }
    });

        $('#country').change(function(){
            if($(this).val()){
                $('#state').html('<option value="">Select State</option>');
                getStates($(this).val());
            }
        });
        $('#state').change(function(){
            if($(this).val() != ""){
                $('#city').html('<option value="">Select City</option>');
                getCities($(this).val());
            }
        })
        function getStates(country){
            $.ajax({
                    type:"post",
                    url:"https://countriesnow.space/api/v0.1/countries/states",
                    data:{
                        "country": "United States"
                    },
                    success:function(response){
                        const states = response.data.states;
                        for(var i=0;i<states.length;i++){
                            // this state does not have cities
                            if(states[i].name != 'American Samoa'){
                                $('#state').append('<option value="'+states[i].name+'">'+states[i].name+'</option>');
                            }
                        }
                    }
            });
        }
        function getCities(state_name){
            $.ajax({
                    type:"post",
                    url:"https://countriesnow.space/api/v0.1/countries/state/cities",
                    data:{
                        "country": "United States",
                        "state": state_name
                    },
                    success:function(response){
                        const cities = response.data;
                        for(var i=0;i<cities.length;i++){
                            $('#city').append('<option value="'+cities[i]+'">'+cities[i]+'</option>');
                        }
                    },
                    error:function(error){

                    }
            });
        }
        // remove from cart
        $(".cross-mark").click(function (e) {
            e.preventDefault();
            $("#deleteModalCart #delete_type").val($(this).parent().attr('data-id'));
            $("#deleteModalCart").modal("show");

        });
        var selected_items=[];
        $('.checkbox').on('click',function(){

            if ($(this).is(':checked')) {
                selected_items.push($(this).attr("id"))
            }
            else{
                const index = selected_items.indexOf($(this).attr('id'));
                if (index > -1) {
                    selected_items.splice(index, 1);
                }
            }
        });

    $('#delete-selected').on('click',function(e){
       e.preventDefault();
       if(selected_items.length==0){

         $("#infoModalCart").modal("show");
       }
        else{
            $("#deleteModalCart #delete_type").val("all_delete");
            $("#deleteModalCart").modal("show");
        }
    });

    $(".delete_cart_item").click(function () {
        if($("#delete_type").val()=="all_delete"){
            $(".delete_cart_item").attr("disabled",true);
            $(".delete_cart_item").text("Please wait");
            $.ajax({
                url: '{{ route('remove-selected-from-cart') }}',
                method: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    ids: selected_items
                },
                success: function (response) {
                    window.location.reload();
                }
            });
        }else{
            var id = $("#delete_type").val();
            $(".delete_cart_item").attr("disabled",true);
            $(".delete_cart_item").text("Please wait");
            $.ajax({
                url: '{{ route('remove.from.cart') }}',
                method: "POST",
                data: {
                    _token: '{{ csrf_token() }}',
                    id: id
                },
                success: function (response) {
                    window.location.reload();
                }
            });
        }
    })
    // select all
    $(".checkAll").click(function(){
        $('.cart_item_main input:checkbox').not(this).prop('checked', this.checked);
        $('.cart_item_main input:checkbox').each(function(){
            selected_items.push($(this).attr("id"));
        })
    });

    $("#use_prezziez_credit").click(function(){
        if(!$(this).is(':checked')){
            var use_credit = "no";
        }else{
            var use_credit = "yes";
        }
        $.ajax({
            type:"get",
            url:"{{route('cart')}}?use_credit="+use_credit,

            success:function(response){
                location.reload()
            },
            error:function(error){
                location.reload();
            }
        });

    });

    $("#use_expedited_shipping").click(function(){
        if(!$(this).is(':checked')){
            var use_credit = "no";
        }else{
            var use_credit = "yes";
        }
        $.ajax({
            type:"get",
            url:"{{route('cart')}}?use_expedited_shipping="+use_credit,

            success:function(response){
                location.reload()
            },
            error:function(error){
                location.reload();
            }
        });

    });
</script>
@endsection
