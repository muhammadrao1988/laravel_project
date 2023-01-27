<li class="nav-item dropdown">
    {{--<a class="nav-link fms-notif-icon"
       data-route="{{ route('notification.store') }}" data-toggle="dropdown"
       href="javascript:void(0)">
        <i class="far fa-bell"></i>
        @if($notifCount = \App\Models\Notification::unread()->count())
            <span
                class="badge badge-danger navbar-badge">{{ @$notifCount }}</span>
        @endif
    </a>--}}
    <div class="dropdown-menu dropdown-menu-noti dropdown-menu-lg dropdown-menu-right">
        <span class="dropdown-header">Notifications</span>
        <div class="dropdown-divider"></div>
        {{--@forelse(\App\Models\Notification::latest()->take(10)->get() as $notif)
            <a href="{{ $notif->url ? url($notif->url) : '' }}" class="dropdown-item dropdown-item-noti truncate-noti"
               data-toggle="tooltip" title="{{ $notif->title }}">
                @if($notif->read_at)
                    <span>{{ $notif->title }}</span>
                @else
                    <span><b>{{ $notif->title }}</b></span>
                @endif
                <span
                    class="float-right text-muted">{{ $notif->created_at->diffForHumans() }}</span>
            </a>
            <div class="dropdown-divider"></div>
        @empty
            <a href="javascript:void(0)" class="dropdown-item dropdown-item-noti">
                {{ __('Not Available') }}
            </a>
            <div class="dropdown-divider"></div>
        @endforelse--}}
    </div>
</li>