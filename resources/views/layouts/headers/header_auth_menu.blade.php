<a href="{{url($auth_user_web->username)}}">
    <li><i class="fa fa-list" aria-hidden="true"></i> My Wishlists</li>
</a>

<a href="{{route('cart')}}">
    <li><i class="fa fa-shopping-cart" aria-hidden="true"></i> My Cart</li>
</a>
<a href="{{route('giftideas')}}">
    <li><i class="fa fa-gift" aria-hidden="true"></i>Gift Guide</li>
</a>
<span class="line-break"></span>
<a href="{{route('my-orders')}}">
    <li><i class="fa fa-briefcase" aria-hidden="true"></i> My Orders</li>
</a>
<a href="{{route('credits')}}">
    <li><i class="fa fa-gift" aria-hidden="true"></i>Credits</li>
</a>
<a href="{{route('myaccount')}}?show=1">
    <li><i class="fa fa-cog" aria-hidden="true"></i> Account Settings</li>
</a>
<span class="line-break"></span>
<a href="{{route('faq')}}">
    <li><i class="fa fa-question-circle" aria-hidden="true"></i> FAQ</li>
</a>
<a href="{{route('about-us')}}">
    <li><i class="fa fa-address-card" aria-hidden="true"></i> About Us</li>
</a>
<a href="{{route('contact-us')}}">
    <li><i class="fa fa-phone" aria-hidden="true"></i> Contact Us</li>
</a>
<a href="{{route('logout')}}">
    <li><i class="fa fa-power-off" aria-hidden="true"></i> Log out</li>
</a>