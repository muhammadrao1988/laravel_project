@if(!empty($wishLists))
    @foreach($wishLists as $wishList)
        @if($wishList->type=="general")
            @php
                $image_type = "knot.png";
                $image_text_class = "";
            @endphp
        @elseif($wishList->type=="registry")
            @php
                $image_type = "on.png";
                $image_text_class = "knot-orange";
            @endphp
        @elseif($wishList->type=="experiences")
            @php
                $image_type = "plane.png";
                $image_text_class = "knot-yellow";
            @endphp
        @elseif($wishList->type=="events")
            @php
                $image_type = "knot.png";
                $image_text_class = "";
            @endphp
        @endif
        @php

            $images_wishlist = $wishList->wishListItems->where('picture','!=','');
            $accept_donation = $wishList->wishListItems->where('accept_donation','=',1)->count();
            $total_wishes = $wishList->wishListItems->count();

        @endphp
        <div class="col-md-4 col-sm-4 col-12 blue-boxes-padd">
            <div class="gift-card-box">
                @if($accept_donation)
                    <div class="ribbon-box">
                        <p class="ribbon-text">CONTRIBUTIONS ACCEPTED</p>
                    </div>
                @endif
                <div class="blue-img">
                    <div class="slider responsive">
                        @if(($images_wishlist->count() > 0))
                            @foreach($images_wishlist as $img)
                                <div>
                                    <a href="{{route('show.wishlist.item',\App\Helpers\Common::encrypt_decrypt($wishList->id))}}">
                                    <img src="{{ asset("storage/uploads/wishlist_item/".$img->picture)}}" alt="image"
                                         class="img-fluid">
                                    </a>
                                </div>
                            @endforeach
                        @else
                            <div>
                                <a href="{{route('show.wishlist.item',\App\Helpers\Common::encrypt_decrypt($wishList->id))}}">
                                <img src="{{asset('image/web/wishlist_placeholder.png')}}" alt="image"
                                     class="img-fluid">
                                </a>
                            </div>
                        @endif
                    </div>


                </div>

                <a href="{{route('show.wishlist.item',\App\Helpers\Common::encrypt_decrypt($wishList->id))}}">
                    <h4 class="gift-card-heading">{{$wishList->title}} <span
                                class="pull-right knot-text {{$image_text_class}}"><img
                                    src="{{asset('image/web/'.$image_type)}}"
                                    alt="image" class="img-fluid knot-img">{{ucfirst($wishList->type)}}</span>
                    </h4>
                </a>
                <a href="{{route('show.wishlist.item',\App\Helpers\Common::encrypt_decrypt($wishList->id))}}"
                   class="christmas-text">
                    {{$total_wishes}}{{$total_wishes > 1 ? " Wishes" : " Wish"}}
                </a>
                    @if($same_profile)
                    <a href="javascript:;" class="text-danger delete_wishlist" data-id="{{\App\Helpers\Common::encrypt_decrypt($wishList->id)}}">
                        <i class="fa fa-trash"></i>
                    </a>
                    @endif
            </div>
        </div>
    @endforeach
@endif
