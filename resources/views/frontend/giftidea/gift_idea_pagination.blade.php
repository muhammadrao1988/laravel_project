@foreach($data as $data)
    <div class="col-md-4 col-sm-6 col-12">
        <div class="main-hover-cls">
            <figure  class="imghvr-slide-down add-to-wishlist"
                     data-attr="{{$data->id}}"
                     data-url="{{$data->item_url}}"
                     data-name="{{$data->item_name}}"
                     data-price="{{$data->price}}"
                     data-shipping="{{$data->shipping_fee}}"
                     data-expedited-shipping="{{$data->expedited_shipping_fee}}"
                     data-merchant="{{$data->merchant}}"
                     data-picture="{{$data->image_path}}"><img
                        src="{{ isset($data->image_path) ? asset("storage/uploads/wishlist_item/".$data->image_path) : '' }}"
                        alt="image" class="img-fluid">
                <div class="item-details">
                    <h4 class="gift-idea-name">{{$data->item_name}}</h4>
                    <span class="gift-idea-price">${{$data->price}}</span>
                </div>

                <div class="pin-hover" style="opacity: 1">
                    <a href="javascriptvoid:(0)" class="add-to-wishlist"

                    ><i class="fa fa-plus" aria-hidden="true"></i></a>
                </div>
            </figure>
        </div>
        <div>
        </div>
    </div>
@endforeach


