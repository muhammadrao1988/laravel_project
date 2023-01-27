@foreach($records as $record)
<tr>
    <td class="table-border-zero">
        @if($auth_user->id == $record->order->user_id)
        <a target="_blank" href="{{route('my-orders.show',$record->order_id)}}">{{$record->order_id}}</a>
        @else
            {{$record->order_id}}
        @endif
    </td>
    <td class="table-border-zero">
        @if($auth_user->id == $record->order->to_user)
            @php($length = count($record->order->orderItems))
            @php($html_item = "")
            @foreach($record->order->orderItems as $OI)

                @if($OI->wishlist_item_id!="")
                    @php($html_item.=' <a target="_blank" href="'.route('show.wishlist.item.detail',\App\Helpers\Common::encrypt_decrypt($OI->wishlist_item_id)).'">'.$OI->item_name.'</a>,')
                @else
                    @php($html_item.=$OI->item_name)
                @endif
            @endforeach
            {!!  rtrim($html_item,",") !!}
        @else
            -
        @endif
    </td>
    <td class="table-border-zero">${{\App\Helpers\Common::numberFormat($record->credit)}}</td>
    <td class="table-border-zero">${{\App\Helpers\Common::numberFormat($record->debit)}}</td>
    <td class="table-border-zero">{{\App\Helpers\Common::CTL($record->created_at,true)}}</td>
    <td class="table-border-zero">
        @if($auth_user->id == $record->order->user_id && $auth_user->id == $record->order->to_user)
            <a target="_blank" href="{{route('my-orders.show',$record->order_id)}}"><i class="fa fa-eye color-cng" aria-hidden="true"></i></a>
        @elseif($auth_user->id == $record->order->to_user)

        @elseif($auth_user->id == $record->order->user_id)
            <a target="_blank" href="{{route('my-orders.show',$record->order_id)}}"><i class="fa fa-eye color-cng" aria-hidden="true"></i></a>

        @endif

    </td>

</tr>
@endforeach
