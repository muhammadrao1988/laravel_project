@extends('layouts.layoutfront')
@section('title', env('APP_NAME').' - Account Settings')
@section('content')
    <!-- ACCOUNT SETTING SECTION BEGIN -->
    <section class="account-setting-sec">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-12">
                    <h4 class="contact-detail-heading mt-0 pt-0">Credits</h4>
                    <p >You have {{$credits}} credits available.</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-12">
                    <div class="order-item-table">

                        <div class="table-responsive border-shade">

                            <table class="table">
                                <thead>
                                <tr>
                                    <th scope="col">Order ID</th>
                                    <th scope="col">Item Name</th>
                                    <th scope="col">Credit</th>
                                    <th scope="col">Debit</th>
                                    <th scope="col">Date</th>
                                    <th scope="col">Action</th>
                                </tr>
                                </thead>
                                <tbody class="order_records">
                                @if(!empty($records))
                                    @include('frontend.myaccount.credit_listing_pagination')
                                @else
                                    <td colspan="4">No records found</td>
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
    <!-- ACCOUNT SETTING SECTION END -->
@endsection
@section('scripts')
    <script>

        function loadMoreData(page, last_page,append_data=true) {
            $.ajax({
                url: '{{route('credits')}}?page=' + page,
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
        @if($last_page > 1)
        $(document).ready(function () {
            //function for Scroll Event
            var page = 1;
            var last_page = "{{$last_page}}";
            last_page = parseInt(last_page);
            $('body').on('click', '#show-more', function () {
                page++; //page number increment
                loadMoreData(page,last_page,true);
            });
        });
        @endif
    </script>
@endsection
