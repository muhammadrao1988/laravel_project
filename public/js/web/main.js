function header_adj() {
    if ($(window).width() < 992) {
        var header_height = $(".header").outerHeight();
        $(".nav-wrap .nav-list").css({ "padding-top": header_height + "px" });
    } else {
        $(".nav-wrap .nav-list").css({ "padding-top": "0" });
    }
}
function submenu_toggle() {
    if ($(window).width() < 992) {
        $(".nav-list li.with-submenu")
            .off()
            .click(function () {
                $(this).toggleClass("is-open");
                $(".submenu").slideToggle("slow");
            });
    }
}
function capitalizeFirstLetter(string){
    return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
}
$(document).ready(function () {

    // Mobile Navigation JS START

    $(".navigation-list ul").each(function () {
        var $this = $(this);
        $this.clone().attr("class", "mobile-nav-items").appendTo(".mobile-body");
    });

    $(".hamburger").on("click", function (e) {
        e.preventDefault();
        var body_element = $("body");

        if ((body_element).hasClass("mobile-view")) {
            body_element.removeClass("mobile-view");
        } else {
            body_element.addClass("mobile-view");
        }
    });

    $(".mobile-close").on("click", function (e) {
        e.preventDefault();
        var body_element = $("body");

        if ((body_element).hasClass("mobile-view")) {
            body_element.removeClass("mobile-view");
        }
    });

    $(document).mouseup(function (e) {
        var container = $(".mobile-nav");
        if (!container.is(e.target) && container.has(e.target).length === 0) {
            if ($("body").hasClass("mobile-view")) {
                $("body").removeClass("mobile-view");
            }
        }
    });

    $(window).resize(function () {
        var $this = $(this),
            win_width = $this.width();

        if (win_width > 990) {
            if ($("body").hasClass("mobile-view")) {
                $("body").removeClass("mobile-view");
            }
        }
    });

    // Mobile Navigation JS END


    // WOW JS

    new WOW().init();
    AOS.init();
    //menu js
    $(".nav-tabs li.nav-item a.nav-link").click(function() {
        $(".nav-tabs li.nav-item a.nav-link").removeClass('active');
    });
    // Add minus icon for collapse element which is open by default
    $(".collapse.show").each(function () {
        $(this)
            .prev(".card-header")
            .find(".fa")
            .addClass("fa-minus")
            .removeClass("fa-plus");
    });

    // Toggle plus minus icon on show hide of collapse element
    $(".collapse")
        .on("show.bs.collapse", function () {
            $(this)
                .prev(".card-header")
                .find(".fa")
                .removeClass("fa-plus")
                .addClass("fa-minus");
        })
        .on("hide.bs.collapse", function () {
            $(this)
                .prev(".card-header")
                .find(".fa")
                .removeClass("fa-minus")
                .addClass("fa-plus");
        });



    // SERVICES-SEC SLICK

    $('.service-slider').slick({
        dots: true,
        arrows: true,
        infinite: false,
        speed: 300,
        slidesToShow: 3,
        slidesToScroll: 1,
        responsive: [{
            breakpoint: 1024,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 1,
                infinite: true,
                dots: true
            }
        },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ]
    });



    // TESTIMONIAL-SEC SLICK

    $('.testimonial-slider').slick({
        dots: true,
        arrows: false,
        infinite: false,
        speed: 300,
        slidesToShow: 3,
        slidesToScroll: 1,
        responsive: [{
            breakpoint: 1024,
            settings: {
                slidesToShow: 2,
                slidesToScroll: 1,
                infinite: true,
                dots: true
            }
        },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ]
    });

    $.validator.messages.required = function (param, input) {

        if (typeof $(input).attr('data-name') !== 'undefined'){
            return  capitalizeFirstLetter($(input).attr('data-name')) + ' field is required';
        }else{
            return  capitalizeFirstLetter(input.name) + ' field is required';
        }

    }

    $(".validation_form").validate({
        errorClass: 'is-invalid',
    });

    header_adj();
    if ($(window).width() < 992) {
        submenu_toggle();
        $(".hamburger").click(function () {
            $(this).toggleClass("is-active");
            $("body,html").toggleClass("sidebar-open");
            $(".nav-wrap").toggleClass("is-open");
        });
        $(".overlay").click(function () {
            $(".hamburger").removeClass("is-active");
            $("body,html").removeClass("sidebar-open");
            $(".nav-wrap").removeClass("is-open");
        });
    } else {
        $(".hamburger").removeClass("is-active");
        $("body,html").removeClass("sidebar-open");
        $(".nav-wrap").removeClass("is-open");
    }

});
$(window).on("resize", function () {
    header_adj();
    submenu_toggle();
    if ($(window).width() < 992) {
        $(".hamburger").click(function () {
            $(this).toggleClass("is-active");
            $("body,html").toggleClass("sidebar-open");
            $(".nav-wrap").toggleClass("is-open");
        });
        $(".overlay").click(function () {
            $(".hamburger").removeClass("is-active");
            $("body,html").removeClass("sidebar-open");
            $(".nav-wrap").removeClass("is-open");
        });
    } else {
        $(".hamburger").removeClass("is-active");
        $("body,html").removeClass("sidebar-open");
        $(".nav-wrap").removeClass("is-open");
    }
});