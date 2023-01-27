<script>
    $(document).ready(function () {
        var page_offer = 1;
        @if(request()->get('gift_offer')==1)
            if(!$("#profile_gift_section").hasClass('active')){
                $("#profile_gift_section").trigger("click");
                loadMoreGiftItem(1);
            }
         @endif
       $("#profile_gift_section").click(function () {
          loadMoreGiftItem(1);
       });

        $('body').on('click', '#show-more-offer', function () {
            page_offer++; //page number increment
            loadMoreGiftItem(page_offer,false);

        });


    })
    function loadMoreGiftItem(page,hide_html=true) {
        if(hide_html) {
            $(".profile_gifted_offers").html("");
        }
        $.ajax({
            url: '{{route('giftOffer')}}?page=' + page,
            type: 'get',
            beforeSend: function () {
                if(hide_html) {
                    $(".loading_offer").show();
                }
                $("#show-more-offer").attr("disabled",true);
                $("#show-more-offer").text("Loading..");
            }
        })
            .done(function (response) {
                $('.loading_offer').hide();
                $("#show-more-offer").removeAttr("disabled");
                $("#show-more-offer").text("See More");
                var last_page = response.last_page;
                if(parseInt(last_page)<=parseInt(page)) {
                    $("#show-more-offer").hide();
                }else{
                    $("#show-more-offer").show();
                }

                if (response.data == "") {
                    if(page==1){
                        $(".profile_gifted_offers").html("<p class='text-center'>No record found</p>");
                    }
                    return;
                }
                if (response.data != "") {
                    $(".profile_gifted_offers").append(response.data);
                }
            })
            // Call back function
            .fail(function (jqXHR, ajaxOptions, thrownError) {
                $("#show-more-offer").removeAttr("disabled");
                $("#show-more-offer").text("See More");
                alert("Server not responding.....");
            });

    }
</script>