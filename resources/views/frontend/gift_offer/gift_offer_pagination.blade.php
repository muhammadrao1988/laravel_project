@foreach($records as $record)
    @foreach($record->orderItems as $item)
        <div class="gift-box-content">
            @if(!empty($item->item_image))
                <img width="99" height="99"
                     src="{{ asset("storage/uploads/offer_gift/".$item->item_image)}}"
                     class="img-fluid" alt="">
            @else
                <img width="99" height="99" src="{{asset('image/web/upload-pre.png')}}" class="img-fluid"
                     alt="avatar">
            @endif
            <div class="gift-box-inner-content">
                <h6 class="gift-box-inner-content-heading">
                    {{$item->item_name}}
                </h6>
                <p class="p-0 m-0" style="font-size: 14px">Gift Offer # {{$record->id}}</p>
                <p class="gift-box-inner-content-text">${{\App\Helpers\Common::numberFormat(($item->item_qty * $item->item_price) + $item->item_shipping_price)}}</p>
                @if(strtolower($item->item_status)=="pending")
                    <button type="button" class="btn btn-sm btn-danger">Pending</button>
                    <a href="{{route('giftOfferDetail',\App\Helpers\Common::encrypt_decrypt($item->id))}}" class="btn btn-sm btn-outline-info">Click here to accept or reject</a>
                @elseif(strtolower($item->item_status)=="accepted")
                    <button type="button" class="btn btn-sm btn-success">Accepted</button>
                    <a href="{{route('giftOfferDetail',\App\Helpers\Common::encrypt_decrypt($item->id))}}" class="btn btn-sm btn-outline-info">View Detail</a>
                @elseif(strtolower($item->item_status)=="declined")
                    <button disabled="" type="button" class="btn btn-sm btn-warning">Declined</button>
                    <a href="{{route('giftOfferDetail',\App\Helpers\Common::encrypt_decrypt($item->id))}}" class="btn btn-sm btn-outline-info">View Detail</a>

                @else
                    <button type="button" class="btn btn-sm btn-info">{{$item->item_status}}</button>
                    <a href="{{route('giftOfferDetail',\App\Helpers\Common::encrypt_decrypt($item->id))}}" class="btn btn-sm btn-outline-info">View Detail</a>
                @endif

            </div>
        </div>
    @endforeach
@endforeach
    