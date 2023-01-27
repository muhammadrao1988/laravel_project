@extends('layouts.layoutfront')
@section('title', 'My-Orders')
@section('content')
    <!-- CART TABLE SECTION BEGIN -->
    <section class="order-item-sec cart-table-sec">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-12">
                    <div class="order-item-table">
                        <h5 class="order-item-content">Orders</h5>
                        <div class="table-responsive border-shade">
                            <div class="row search-row">
                                <div class="col-md-6 col-sm-6 col-12">
                                    <div class="number-box">
                                        <p>Show
                                            <select id="show_entry" class="form-control">
                                                <option {{$show_entry==25 ? "selected" : ""}} value="25">25</option>
                                                <option {{$show_entry==50 ? "selected" : ""}} value="50">50</option>
                                                <option {{$show_entry==100 ? "selected" : ""}} value="100">100</option>
                                            </select>

                                                <span>entries</span>
                                        </p>
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-12">
                                    <div class="box-search">
                                        <input type="text" class="form-control" id="search_order">
                                        <div class="box-search-icon">
                                            <a href="javascriptvoid:(0)"><i class="fa fa-search" aria-hidden="true"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">Id</th>
                                    <th scope="col">Giftee</th>
                                    <th scope="col">Total amount</th>
                                    <th scope="col">Status</th>
                                    <th scope="col">Created At</th>
                                    <th scope="col">Action</th>
                                </tr>
                                </thead>
                                <tbody class="order_records">
                                    @if(!empty($records))
                                        @include('frontend.order.order_listing_pagination')
                                    @else
                                        <td colspan="6">No records found</td>
                                    @endif
                                </tbody>
                            </table>
                            @if($last_page > 1)
                                <button style="width: 100%; cursor: pointer"
                                        class="pt-3 pb-3 front-buttn text-center cursor-pointer mb-4"
                                        id="show-more">See More
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- CART TABLE SECTION END -->
@endsection
@section('scripts')
<script>
    var show_entry = "{{$show_entry}}";
    function loadMoreData(page, last_page,search_text="",show_entry_data=25,append_data=true) {
        $.ajax({
            url: '{{route('my-orders')}}?page=' + page+"&search="+search_text+"&show_entry="+show_entry_data,
            type: 'get',
            beforeSend: function () {
                $("#show-more").attr("disabled", true);
                $("#show-more").text("Loading..");
            }
        }).done(function (data) {

            $("#show-more").removeAttr("disabled");
            $("#show-more").text("See More");

            if (parseInt(last_page) <= parseInt(page)) {
                $("#show-more").hide();
            } else {
                $("#show-more").show();
            }
            if (data == "" && append_data==false) {
                $(".order_records").html("");

                return;
            }else if(data==""){
                return;
            }

            if (data != "" && append_data == true) {
                $(".order_records").append(data);
            }else if(data!=""){
                $(".order_records").html(data);
            }


        })
        // Call back function
            .fail(function (jqXHR, ajaxOptions, thrownError) {
                $("#show-more").removeAttr("disabled");
                $("#show-more").text("See More");
                alert("Server not responding.....");
            });

    }
    $("#show_entry").change(function () {
        location.href = "{{route('my-orders')}}?show_entry="+$(this).val();
    });
    $("#search_order").keyup(function(){
        if($(this).val().length > 1){
            var last_page = "{{$last_page}}";
            last_page = parseInt(last_page);
            loadMoreData(1,last_page,$(this).val(),show_entry,false);
        }else if($(this).val().length == 0){
            var last_page = "{{$last_page}}";
            last_page = parseInt(last_page);
            loadMoreData(1,last_page,"",show_entry,true);
        }
    });
    @if($last_page > 1)
    $(document).ready(function () {
        //function for Scroll Event
        var page = 1;
        var last_page = "{{$last_page}}";
        last_page = parseInt(last_page);
        $('body').on('click', '#show-more', function () {
            page++; //page number increment
            var search= $("#search_order").val();
            loadMoreData(page,last_page,search,show_entry,true);
        });
    });
    @endif
</script>
@endsection
