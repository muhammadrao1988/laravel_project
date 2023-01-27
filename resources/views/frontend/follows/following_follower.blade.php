@extends('layouts.layoutfront')
@section('css')
    <style>
        .following-boxes img {
            display: block;
            margin: 0 auto
        }

        .following-boxes a {
            font-size: inherit
        }
        .following-boxes a:hover {
            color: #DBAEAC
        }
    </style>
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
    <!-- FOLLOWER AND FOLLOWING SECTION BEGIN -->
    <section class="inner-main-sec">
        <div class="container">
            <h2 class="foll-heading">{{$data['heading']}}</h2>
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link {{$data['active_following']}}" href="{{route('following')}}">I'm Following</a>
                </li>
                <li class="nav-item">
                    <span class="base-color">|</span>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{$data['active_follower']}}" href="{{route('followers')}}">Following Me</a>
                </li>
            </ul>
            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane active" id="home" role="tabpanel">
                    <div class="row follow_records">
                        @include('frontend.follows.following_follower_pagination')
                    </div>
                    @if($last_page > 1)
                        <button style="width: 100%; cursor: pointer"
                                class="pt-3 pb-3 front-buttn text-center cursor-pointer mb-4"
                                id="show-more">See More
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <!-- FOLLOWER AND FOLLOWING SECTION END -->
    <!-- Cancel send request-->
    <div class="modal popup-modal fade" id="followOperationModal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="modal-body-content">

                        <img src="{{asset('image/web/placeholder.png')}}" class="img-fluid dynamic_img"
                             alt="avatar">

                        <h4 class="unfol-joe dynamic_name"></h4>
                        <input type="hidden" class="profile_url" value="">
                        <input type="hidden" class="operation_type" value="">

                        <div class="modal-unfollow-buttn">
                            <button type="button" class="unfol-joe-col confirm_operation_btn">Confirm</button>
                            <button type="button" class="unfol-joe-trans" data-dismiss="modal">CANCEL</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('frontend.follows.follower_accept_reject_modal')

@endsection
@section('scripts')
    <script src="{{ asset('js/helper.js')}}"></script>
    <script>
        function resetOperationModal() {
            $("#followOperationModal .dynamic_img").attr("src", "");
            $("#followOperationModal .dynamic_name").html("");
            $("#followOperationModal .profile_url").val("");
            $("#followOperationModal .operation_type").val("");

            $("#acceptRejectModal .dynamic_img").attr("src", "");
            $("#acceptRejectModal .dynamic_name").html("");
            $("#acceptRejectModal .profile_url").val("");
            $("#acceptRejectModal .operation_type").val("");
        }

        $("body").on("click", ".operation_modal", function () {
            var operation_type = $(this).attr('data-operation-type');
            var profile_url = $(this).attr('data-profile-url');
            var dynamic_name = $(this).attr('data-dynamic-name');
            var dynamic_img = $(this).attr('data-dynamic-img');
            resetOperationModal();
            if (operation_type == "acceptRejectRequest") {
                $("#acceptRejectModal .dynamic_img").attr("src", dynamic_img);
                $("#acceptRejectModal .dynamic_name").html(dynamic_name);
                $("#acceptRejectModal .profile_url").val(profile_url);
                $("#acceptRejectModal .operation_type").val("");
                $("#acceptRejectModal").modal("show");
            } else {
                $("#followOperationModal .dynamic_img").attr("src", dynamic_img);
                $("#followOperationModal .dynamic_name").html(dynamic_name);
                $("#followOperationModal .profile_url").val(profile_url);
                $("#followOperationModal .operation_type").val(operation_type);
                $("#followOperationModal").modal("show");
            }

        });
        $("body").on("click", ".acceptRejectBtn", function () {
            $("#acceptRejectModal .operation_type").val($(this).attr('data-value'));
            setTimeout(function () {
                followOperation($("#acceptRejectModal .operation_type").val(), $("#acceptRejectModal .profile_url").val(), 'acceptRejectModal');

            }, 500);

        });

        $("body").on("click", ".confirm_operation_btn", function () {
            followOperation($("#followOperationModal .operation_type").val(), $("#followOperationModal .profile_url").val(), 'followOperationModal');

        });

        function loadMoreData(page, last_page) {
            $.ajax({
                url: '{{route($data['route_name'])}}?page=' + page,
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
                if (data == "") {

                    return;
                }

                if (data != "") {
                    $(".follow_records").append(data);
                }


            })
            // Call back function
                .fail(function (jqXHR, ajaxOptions, thrownError) {
                    $("#show-more").removeAttr("disabled");
                    $("#show-more").text("See More");
                    alert("Server not responding.....");
                });
        }

        function followOperation(operaionType, profileUrl, modal_id) {
            var operation_type = operaionType;
            var profileUrl = profileUrl;
            var redirect_url = "{{route($data['route_name'])}}";
            var data = {
                'operation_type': operaionType,
                "username": profileUrl,
                "_token": "{{csrf_token()}}",
                "redirect_url": redirect_url
            };
            $.easyAjax({
                url: "{{ route('follow_operation') }}",
                type: "POST",
                data: data,
                container: "#" + modal_id + " .modal-body",
                messagePosition: "toastr",
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