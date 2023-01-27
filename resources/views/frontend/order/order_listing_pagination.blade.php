@foreach($records as $record)
<tr>
    <td class="table-border-zero">{{$record->id}}</td>
    <td class="table-border-zero"><a target="_blank" class="amount-blue-col" href="{{route('profileUrl',$record->username)}}">{{$record->displayName}}</a></td>
    <td class="table-border-zero">${{\App\Helpers\Common::numberFormat($record->total_amount)}}</td>
    <td class="table-border-zero ord-processing"><span>{{$record->status}}</span></td>
    <td class="table-border-zero">{{\App\Helpers\Common::CTL($record->created_at)}}</td>
    <td class="table-border-zero"><a href="{{route('my-orders.show',$record->id)}}"><i class="fa fa-eye color-cng" aria-hidden="true"></i></a></td>
</tr>
@endforeach
