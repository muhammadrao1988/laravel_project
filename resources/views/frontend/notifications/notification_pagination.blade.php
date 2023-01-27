@foreach($notifications as $notif)
    <div class="gift-box-content">

        @if($notif->from_user_id > 0)
            <a href="javascript:;" class="notification_read" data-url="{{$notif->url}}" data-id="{{$notif->id}}" data-status="{{$notif->read_at=="" ? "unread" : ""}}">
                @if($notif->profile_image!="")
                    <img  width="99" height="99" style="border-radius: 10px" src="{{ asset("storage/uploads/profile_picture/".$notif->profile_image)}}"
                          class="img-fluid" alt="">
                @else
                    <img width="99" height="99" style="border-radius: 10px" src="{{asset('image/web/placeholder.png')}}" class="img-fluid">
                @endif
            </a>
        @endif
        <a href="javascript:;" class="notification_read" data-url="{{$notif->url}}" data-id="{{$notif->id}}" data-status="{{$notif->read_at=="" ? "unread" : ""}}">
            <div class="gift-box-inner-content">
                <h6 class="gift-box-inner-content-heading">{{$notif->title}}</h6>
                <p class="gift-box-inner-content-text">{{\App\Helpers\Common::humanReadTime($notif->created_at,$auth_user->timezone)}}</p>
            </div>
        </a>
    </div>
@endforeach