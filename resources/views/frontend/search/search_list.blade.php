@if(!empty($profile_detail_search_data))
    @if(!empty($show_heading))
        <h2 style="margin-bottom: 5px; font-size:14px; color:#777; margin-top:10px;">People</h2>
    @endif
<table class="search-result-table" width="100%" cellspacing="0" cellpadding="0">

        @if($profile_detail_search_data->profile_image!="")
            @php($img_url = asset("storage/uploads/profile_picture/".$profile_detail_search_data->profile_image))
        @else
            @php($img_url = asset("image/web/placeholder.png"))

        @endif
        <tr valign="middle">
            <td width="30" style="border-top:1px solid #eee; padding:10px 0px;">
                <a href="{{route('profileUrl',$profile_detail_search_data->username)}}">
                    <img border="0" class="img-circle" src="{{$img_url}}" width="30" />
                </a>
            </td>
            <td onclick="document.location.href='{{route('profileUrl',$profile_detail_search_data->username)}}';"
                style=" padding:10px 10px; cursor:pointer; border-top:1px solid #eee;">
                <div style="font-weight:500; margin: 0px; font-size:15px;">{{$profile_detail_search_data->displayName}}</div>
            </td>
            <td style=" padding:10px 0px; text-align: right;  border-top:1px solid #eee;">

            </td>
        </tr>

</table>
@elseif(!empty($no_record))
    <h2 style="margin-bottom: 5px; font-size:14px; color:#777; margin-top:10px;">No record found</h2>
@endif