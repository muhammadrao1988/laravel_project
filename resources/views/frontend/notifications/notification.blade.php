@extends('layouts.layoutfront')
@section('css')
@endsection
@section('content')
    <section class="profile-banner-sec no-wishes-banner">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-12">
                </div>
            </div>
        </div>
    </section>
    <section class="inner-main-sec">
        <div class="container">
            <h2 class="foll-heading">Notifications</h2>
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link {{$all_notification}}" href="{{route('notifications')}}">All Notifications</a>
                </li>
                <li class="nav-item">
                    <span class="base-color">|</span>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{$unread_notification}}" href="{{route('notifications')}}?show_unread=1">Unread Notifications</a>
                </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane active" role="tabpanel">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-12 append_div">
                           @include('frontend.notifications.notification_pagination')
                        </div>
                    </div>
                    @if($last_page > 1)
                        <button style="width: 100%; cursor: pointer" class="pt-3 pb-3 front-buttn text-center cursor-pointer mb-4"
                                id="show-more">See More
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </section>


@endsection
@section('scripts')
    <script src="{{ asset('js/helper.js')}}"></script>
    <script>
        function loadMoreData(page,last_page) {
            $.ajax({
                url: '{{route('notifications')}}?page=' + page,
                type: 'get',
                beforeSend: function () {
                    $("#show-more").attr("disabled",true);
                    $("#show-more").text("Loading..");
                }
            })
                .done(function (data) {
                    //$('body .responsive').slick({});
                    $("#show-more").removeAttr("disabled");
                    $("#show-more").text("See More");

                    if(parseInt(last_page)<=parseInt(page)) {
                        $("#show-more").hide();
                    }else{
                        $("#show-more").show();
                    }
                    if (data == "") {

                        return;
                    }

                    if (data != "") {
                        $(".append_div").append(data);
                    }


                })
                // Call back function
                .fail(function (jqXHR, ajaxOptions, thrownError) {
                    $("#show-more").removeAttr("disabled");
                    $("#show-more").text("See More");
                    alert("Server not responding.....");
                });

        }
        $(document).ready(function () {
            //function for Scroll Event
            var page = 1;
            var last_page = "{{$last_page}}";
            last_page = parseInt(last_page);
            $('body').on('click', '#show-more', function () {
                page++; //page number increment
                loadMoreData(page,last_page);
            });
        })
    </script>
@endsection
<!-- FOOTER SECTION END -->
