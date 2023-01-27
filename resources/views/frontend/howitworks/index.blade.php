@extends('layouts.layoutfront')

@section('content')
    <!-- Muhammad Asaad How It Works page Start in HTML -->

    <!-- Banner Section work start -->
    <section class="parent_banner_section_how_works">
        <div class="container">
            <div class="main_banner_how_works">
                <div class="row">
                    <div class="col-12">
                        <h1>How it works</h1>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Banner Section work end -->

    <!-- Giftees section Head-->
    <section class="main_giftees_section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2>Giftees <span class="giftee-bracket-text">(Those receiving gifts)</span></h2>
                </div>
            </div>
        </div>
    </section>
    <!-- Giftees section Head End-->

    <!-- Signup section mobile work start -->
    <section class="main_signup_section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <div class="main_signup_section_description">
                        <ul>
                            <h3>Sign up for Prezziez</h3>

                            <h3>Fill out your personal information. This includes:</h3>
                            <li>
                                <i class="fa fa-circle" aria-hidden="true"></i>
                                <p>The name you’d like to receive your Prezziez under (this will be kept private) and your username (this will be public)</p>
                            </li>
                            <li>
                                <i class="fa fa-circle" aria-hidden="true"></i>
                                <p>The shipping address you’d like to receive your prezziez</p>
                            </li>
                            <li>
                                <i class="fa fa-circle" aria-hidden="true"></i>
                                <p>Your name and shipping address will not be shared with the gifter</p>
                            </li>

                            <h3>Choose whether you’d like to receive gift offers or not</h3>
                            <li>
                                <i class="fa fa-circle" aria-hidden="true"></i>
                                <p>Gift offers are exactly what they sound like. It is an option that allows a gifter to offer a gift to you that isn’t on your wishlist.</p>
                            </li>
                            <li>
                                <i class="fa fa-circle" aria-hidden="true"></i>
                                <p>Gift offers are already pre-paid by the gifter.</p>
                            </li>
                            <li>
                                <i class="fa fa-circle" aria-hidden="true"></i>
                                <p> When a gift offer comes in all you have to do is decide whether you’d like to accept or deny the offer. You will be able to specify what color, size, variation etc. of the gift offer that you would like</p>
                            </li>
                            <li>
                                <i class="fa fa-circle" aria-hidden="true"></i>
                                <p> Prezziez fulfills the gift offers that you accept. If you decline the gift offer, the gifter will be refunded.</p>
                            </li>

                            <h3>Choose if you would like to allow Prezziez to fulfill orders for items that are outpriced or out of stock using comparable merchants</h3>
                            <li>
                                <i class="fa fa-circle" aria-hidden="true"></i>
                                <p>For example: Let’s say you have a $50 item from WalMart on your wishlist that has been purchased for you by a gifter. Upon attempting to fulfill this order, Prezziez sees that the item is either priced higher than the initial purchase price or is no longer available from the desired merchant. The item is however available at Target or Amazon for the same initial price.</p>
                            </li>
                            <li>
                                <i class="fa fa-circle" aria-hidden="true"></i>
                                <p>By allowing this feature you allow Prezziez to fulfill the order by purchasing the desired item from comparable merchants. Doing this will keep the probability of an order being canceled due to stock inventory or outpricing down.</p>
                            </li>
                            <li>
                                <i class="fa fa-circle" aria-hidden="true"></i>
                                <p>Prezziez will only ever use comparable merchants. i.e. If the original merchant was Neiman Marcus a comparable merchant would be Bloomingdales. If the original merchant was Ulta a comparable merchant would be Sephora and so forth.</p>
                            </li>
                            <li>
                                <i class="fa fa-circle" aria-hidden="true"></i>
                                <p>You may opt out of this feature at any time in your settings.</p>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <div class="main_signup_section_image">
                        <img class="img-fluid" src="{{asset('image/web/w1.png')}}" />
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Signup section mobile work end -->

    <!-- Website Search Section Work start -->
    <section class="main_web_search_section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <div class="parent_web_search_section_image">
                        <img class="img-fluid" src="{{asset('image/web/hw2.png')}}" />
                    </div>
                </div>

                <div class="col-lg-6 col-md-12 col-sm-12">
                    <div class="parent_web_search_description">
                        <ul>
                            <h3>Add as many wishlist items from any e-commerce website by copy and pasting its URL</h3>
                            <li>
                                <i class="fa fa-circle" aria-hidden="true"></i>
                                <p>You can have multiple wishlists that can be customized and separated into different categories (Birthday, Christmas, Anniversary, etc.)</p>
                            </li>
                            <li>
                                <i class="fa fa-circle" aria-hidden="true"></i>
                                <p>You may choose to allow giftees to donate towards the total cost of your items. This is ideal for high-cost gifts! (Note that items must be a minimum of $150 in order to activate this option)</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Website Search Section Work End -->

    <!--Share WishList for Experience and Expenses Start  -->
    <section class="main_share_wishlist_section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <div class="share_wishlist_description">
                        <ul>
                            <h3>The final step is to share your wishlist and wait for your prezziez to arrive!</h3>
                            <li>
                                <i class="fa fa-circle" aria-hidden="true"></i>
                                <p>All orders will be placed within 10 business days of receiving payment from the gifter</p>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <div class="share_wishlist_image">
                        <img class="img-fluid" src="{{asset('image/web/hw3.png')}}" />
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--Share WishList for Experience and Expenses End  -->

    <!-- Giftees section Head-->
    <section class="main_giftees_section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h2>Gifters <span class="giftee-bracket-text">(Those purchasing gifts)</span></h2>
                </div>
            </div>
        </div>
    </section>
    <!-- Giftees section Head End-->

    <!-- Find Wishlist section Work Start -->
    <section class="main_find_wishlist_section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <div class="main_find_wishlist_description">
                        <ul>
                            <h3>Go to Prezziez and find the wishlist you’re looking for. Sign up is encouraged, but not at all necessary.</h3>
                            <li>
                                <i class="fa fa-circle" aria-hidden="true"></i>
                                <p>You can either get to a wishlist by direct link or looking up the username of the profile the wishlist is attached to</p>
                            </li>

                            <h3>Choose the prezzie you’d like to purchase for your giftee</h3>
                            <li>
                                <i class="fa fa-circle" aria-hidden="true"></i>
                                <p>Enter in the payment details for the gift</p>
                            </li>

                            <li>
                                <i class="fa fa-circle" aria-hidden="true"></i>
                                <p>Gift total amount will include any applicable fees, estimated taxes and shipping & handling for the gift</p>
                            </li>
                            <li>
                                <i class="fa fa-circle" aria-hidden="true"></i>
                                <p>If an item allows for donations, you may choose to either cover the full cost of the item or customize the exact amount you would like to donate.</p>
                            </li>
                            <li class="sub_list">
                                <i class="fa fa-circle" aria-hidden="true"></i>
                                <p>Note: Any applicable fees will be added to whatever amount you choose to contribute </p>
                            </li>
                            
                        </ul>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <div class="main_find_wishlist_image">
                        <img class="img-fluid" src="{{asset('image/web/hw4.png')}}" />
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Find Wishlist section Work End -->

    <!-- Chat section Work Start -->
    <section class="main_chat_section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <div class="main_chat_image">
                        <img class="img-fluid" src="{{asset('image/web/hw5.png')}}" />
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <div class="main_chat_description">
                        <ul>
                            <h3>If you would like to gift a prezzie that is not on the giftee’s list, you may opt to send them a gift offer</h3>
                            <li>
                                <i class="fa fa-circle" aria-hidden="true"></i>
                                <p>Go to the giftee’s profile and click on the icon that says “Offer a Gift”.</p>
                            </li>
                            <li>
                                <i class="fa fa-circle" aria-hidden="true"></i>
                                <p>Copy and paste the URL of the item you would like to gift</p>
                            </li>
                            <li>
                                <i class="fa fa-circle" aria-hidden="true"></i>
                                <p>Pay for the item just as you would a regular prezzie, attach a note to the offer and send!</p>
                            </li>
                            <li>
                                <i class="fa fa-circle" aria-hidden="true"></i>
                                <p>The giftee will receive your offer and either accept or decline the offer.</p>
                            </li>
                            <li>
                                <i class="fa fa-circle" aria-hidden="true"></i>
                                <p>If declined you will receive a 100% refund minus service and processing fees refunded back to the original payment method.</p>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Chat section Work end -->

    <!-- THAT'S IT SECTION WORK START -->
    <section class="main_thats_it_section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12">
                    <div class="main_thats_it_description">
                        <ul>
                            <h3>That’s it! We’ve got it from there!</h3>
                            <li>
                                <i class="fa fa-circle" aria-hidden="true"></i>
                                <p>All orders will be placed within 10 business days of receiving your payment.</p>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-6 col-md-12 col-sm-12">
                    <div class="main_thats_it_image">
                        <img class="img-fluid" src="{{asset('image/web/hw6.png')}}" />
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- THAT'S IT SECTION WORK END -->

    <!-- CONTACT US WORK SECTION START -->
    <section class="main_contact_us_section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="parent_contact_desccription">
                        <h3>Still have more questions?</h3>
                        <div class="contact_admin">
                            <h5>Contact us at:</h5>
                            <a href="mailto:admin@prezziez.com">admin@prezziez.com</a>
                        </div>
                        <div class="faq_part">
                            <p>or <a href="{{route('faq')}}">view our FAQ page</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- CONTACT US WORK SECTION END -->

    <!-- Muhammad Asaad How It Works page End in HTML -->
@endsection
