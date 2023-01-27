<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ asset('image/web/favicon-prezziez.png') }}">
    <title>@yield('title','Prezziez')</title>
    <meta name="robots" content="noimageindex, nofollow, noindex">
    <meta name="keywords" content="@yield('keywords','Prezziez')">
    <meta name="description" content="@yield('description','Prezziez')">
    @yield('fb_tag')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/css/bootstrap.min.css"
          integrity="sha384-B0vP5xmATw1+K9KRQjQERJvTumQW0nPEzvF6L/Z6nronJ3oUOFUFpCjEUQouq2+l" crossorigin="anonymous">
    <link href="{{ asset('css/web/main.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" integrity="sha512-9usAa10IRO0HhonpyAIVpjrylPvoDwiPUiKdWk5t3PyolY1cOd4DSE0Ga+ri4AuTroPR5aQvXU9xC6qOPnzFeg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
    <link rel="stylesheet" href="{{asset('css/web/jquery.fancybox.min.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/web/slick.css')}}"/>
    <link rel="stylesheet" type="text/css" href="{{asset('css/web/slick-theme.css')}}"/>
    <link rel="stylesheet" href="{{ asset('css/web/animate.css')}}">
    <link rel="stylesheet" href="{{ asset('css/web/owl.carousel.min.css')}}">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css"  rel="stylesheet"/>
    @yield('css')
</head>

<body class="@yield('body_class')">
<div class="preloader" id="preloader">
</div>
@if(!empty($auth_user_web))
    @if($isMobile)
        @include('layouts.headers.header_auth_mobile')
    @else
        @include('layouts.headers.header_auth')
    @endif
@else
    @if($isMobile)
        @include('layouts.headers.header_non_auth_mobile')
    @else
        @include('layouts.headers.header_non_auth')
    @endif
@endif
<div class="container" style="@yield('alert_display')">

</div>
@yield('content')
<!-- FOOTER SECTION BEGIN -->
<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-sm-4 col-12">
                <div class="footer-logo-content">
                    <a href="/" class="footer-logo"><img src="{{asset('image/web/logo.png')}}" alt="image" class="img-fluid"></a>
                    <p class="footer-text">Prezziez is your go to gifting platform that makes the gift giving process seamless, easy and most importantly attainable!</p>
                </div>
            </div>
            <div class="col-md-2 col-sm-4 col-12">
                <div class="quick-links-text">
                    <h6 class="quick-links-heading">Quick Links</h6>
                    <ul class="links-items">
                        <li class="quick-links-sub-text">
                            <a href="{{route('about-us')}}" class="quick-links-inner">About Us</a>
                        </li>
                        <li class="quick-links-sub-text">
                            <a href="{{route('howitworks')}}" class="quick-links-inner">How It Works</a>
                        </li>
                        <li class="quick-links-sub-text">
                            <a href="{{route('faq')}}" class="quick-links-inner">FAQ</a>
                        </li>
                        <li class="quick-links-sub-text">
                            <a href="{{route('contact-us')}}" class="quick-links-inner">Contact Us</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-2 col-sm-4 col-12">
                <div class="quick-links-text">
                    <h6 class="quick-links-heading">Legal</h6>
                    <ul class="links-items">
                        <li class="quick-links-sub-text">
                            <a href="{{route('privacy-policy')}}"
                               class="quick-links-inner">Privacy Policy</a>
                        </li>
                        <li class="quick-links-sub-text">
                            <a href="{{route('terms-condition')}}"
                               class="quick-links-inner">Terms and Conditions</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-2 col-sm-4 col-12">
                <div class="quick-links-text">
                    <h6 class="quick-links-heading">Follow Us</h6>
                    <ul class="main-icons-link">
                        <li class="social-icons">
                            <a href="https://www.facebook.com/prezziez/?ref=page_internal" target="_blank" class="social-icons-imgs">
                                <i class="fa-brands fa-facebook" aria-hidden="true"></i>
                            </a>
                        </li>
                        <li class="social-icons">
                            <a href="https://www.instagram.com/prezziezofficial/" target="_blank" class="social-icons-imgs">
                                <i class="fa-brands fa-instagram" aria-hidden="true"></i>
                            </a>
                        </li>
                        <li class="social-icons">
                            <a href="https://twitter.com/prezziez" target="_blank" class="social-icons-imgs">
                                <i class="fa-brands fa-twitter" aria-hidden="true"></i>
                            </a>
                        </li>
                        <li class="social-icons">
                            <a href="https://www.tiktok.com/@prezziez" target="_blank" class="social-icons-imgs">
                                <i class="fa-brands fa-tiktok"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-md-2 col-sm-2 col-12">
            </div>
        </div>
    </div>
</footer>
<!-- FOOTER SECTION END -->
<!-- COPY RIGHT SECTION BEGIN -->
<section class="copy-right-sec">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-12">
                <ul class="copy-sec-links">
                    <li class="copy-sec-text">© {{date('Y')}} prezziez</li>
                    <li class="copy-sec-text"><span class="copy-line">|</span></li>
                    <li class="copy-sec-text">All rights reserved</li>
                    <li class="copy-sec-text"><span class="copy-line">|</span></li>
                    <li class="copy-sec-text">Powered by clickysoft</li>
                </ul>
            </div>
        </div>
    </div>
</section>
<!-- COPY RIGHT SECTION END -->
<script
        src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Piv4xVNRyMGpqkS2by6br4gNJ7DXjqk09RmUpJ8jgGtD7zP9yug3goQfGII0yAns"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@toastr_render
<script type="text/javascript" src="{{ asset('js/web/slick.min.js')}}"></script>
<script src="{{ asset('js/web/owl.carousel.min.js')}}"></script>
<script src="{{ asset('js/web/wow.min.js')}}"></script>
<script src="{{ asset('js/web/jquery.fancybox.min.js')}}"></script>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script src="{{ asset('js/web/main.js')}}"></script>
<script src="{{asset('js/state-cities.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" ></script>

<script>
    function changeimg(url, e) {
        document.getElementById("img").src = url;
        let nodes = document.getElementById("thumb_img");
        let img_child = nodes.children;
        for (i = 0; i < img_child.length; i++) {
            img_child[i].classList.remove('active')
        }
        e.classList.add('active');
    }
</script>

{{-- loaderjs --}}
<script>
    $(window).on('load', function () {
// preloader
        $("#preloader").addClass('hide');
        $('#preloader').fadeOut(100);
        $('[data-toggle="tooltip"]').tooltip();
    });
    @if(!empty($auth_user_web))
    $(".notification_read,.read_all_notifications").click(function () {
        var className = $(this).attr('class');
        if(className=="read_all_notifications"){
            var redirect_url = "reload";
            var notification_id = -1;
            var status ="read";
        }else {
            var redirect_url = $(this).attr('data-url');
            var notification_id = $(this).attr('data-id');
            var status = $(this).attr('data-status');
        }
        if(status=="unread" || className=="read_all_notifications"){
            var data = {
                'id': notification_id,
                '_token': "{{csrf_token()}}",
                'className': className,
            };
            $.ajax({
                url: "{{route('notification.read')}}",
                dataType: "json",
                type: "POST",
                data: data
            }).then(function (data) {

            }).fail(function (error) {

            });
        }
        setTimeout(function () {
            if(redirect_url=="reload"){
                location.reload();
            }else{
                location.href = redirect_url;
            }
        },1000);


    });
    $(".notification_setting").click(function () {
        location.href = "{{route('myaccount')}}?show=1";
    })
    @endif
    $(".searchbox-field").focus(function(){
        var search_data = $("#quicksearch-results .results").html();
        search_data = search_data.replace(/\s/g, "");

        if(search_data.length > 0){
            $("#quicksearch-results").show();
        }

    });
    $('.searchbox-field').keyup(function(e){
        e.preventDefault();
        if($(this).val().length >=2 ) {
            $("#quicksearch-results .results").html("");
            $(".header-search-loader").show();
;            $.ajax({
                type: "get",
                url: "{{route('get-giftees')}}",
                data: {q: $(this).val()},
                success: function (response) {
                    console.log(response);
                    $("#quicksearch-results .results").html(response.data);
                    $("#quicksearch-results").show();
                    $(".header-search-loader").hide();
                }
            })
        }
    });
    $(document).mouseup(function(e)
    {
        var container = $(".search-box");

        // if the target of the click isn't the container nor a descendant of the container
        if (!container.is(e.target) && container.has(e.target).length === 0)
        {
            $("#quicksearch-results").hide();
        }
    });


    @if(session('success'))
        toastr.success("{{session('success')}}", "Success");
    @endif
    @if(session('error'))
        toastr.error("{{session('error')}}", "Error");
    @endif
    @if(session('info'))
    toastr.info("{{session('info')}}", "Info");
    @endif

</script>
<!-- Core theme JS-->
@yield('scripts')
</body>
</html>
