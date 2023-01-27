@foreach($records as $record)
    <div class="col-md-4 col-sm-4 col-12 mt-5">
        <div class="following-boxes">
            @if($record->$relation_name->profile_image!="")
                @php($img_url = asset("storage/uploads/profile_picture/".$record->$relation_name->profile_image))
                <img src="{{ $img_url}}" class="img-fluid" alt="">
            @else
                @php($img_url = asset("image/web/placeholder.png"))
                <img src="{{$img_url}}" class="img-fluid">
            @endif
            <h4 class="john-heading"><a href="{{route('profileUrl',$record->$relation_name->username)}}">{{$record->$relation_name->displayName}}</a></h4>
            <span class="time-text">{{$record->$relation_name->wishlist_count}} {{($record->$relation_name->wishlist_count > 1 ? "Wishlists" : "Wishlist") }}</span>
                @if($data['route_name']=="following")
                    @if($record->following_status=="Pending")
                        @php($button_text = "Pending")
                        @php($modal_target = "cancel_request")
                        @php($dynamic_text = "Cancel Request?")
                    @else
                        @php($button_text = "Following")
                        @php($modal_target = "unfollow_following_request")
                        @php($dynamic_text = "Unfollow ".$record->$relation_name->displayName."?")
                    @endif
                @else
                    @if($record->following_status=="Pending")
                        @php($button_text = "Pending Request")
                        @php($modal_target = "acceptRejectRequest")
                        @php($dynamic_text = "Accept ".$record->$relation_name->displayName." follow request?")
                    @else
                        @php($button_text = "Following")
                        @php($modal_target = "unfollow_request")
                        @php($dynamic_text = "Remove ".$record->$relation_name->displayName."?")
                    @endif
                @endif
            <button class="btn operation_modal" data-profile-url="{{$record->$relation_name->username}}" type="button" data-operation-type="{{$modal_target}}" data-dynamic-img="{{$img_url}}" data-dynamic-name="{{$dynamic_text}}" >{{$button_text}}</button>
        </div>
    </div>
@endforeach