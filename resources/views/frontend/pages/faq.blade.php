@extends('layouts.layoutfront')
@section('title', env('APP_NAME').' - FAQ')
@section('content')
    <!-- FAQ SECTION BEGIN -->
    <section class="faq-sec">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12 col-12">
                    <h2 class="faq-heading">Frequently Asked Questions</h2>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-12">
                    <div class="faq-question-sec">
                        <div class="demo">
                            <div class="accordion" id="accordionExample">
                                <div class="faqs">
                                    <div class="card-header" id="headingOne">
                                        <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapseOne"><h2 class="mb-0 accordian-heading"><i class="fa fa-plus"></i> How does it work?</h2></button>
                                    </div>
                                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <p class="mb-1"> <i class="fa fa-circle" aria-hidden="true"></i> <strong>Step 1:</strong> Sign up and create your wishlist! Create wishlists and registries by pasting the URL of the desired item. Big ticket items are eligible for friends and families to contribute towards!</p>
                                            <p><strong><i class="fa fa-circle" aria-hidden="true"></i> Step 2:</strong> Customize your wishlists and registries to your liking and share! Those who you have shared your wishlist with are able to purchase gifts and make contributions for you.</p>
                                            <p><strong><i class="fa fa-circle" aria-hidden="true"></i> Step 3:</strong> Prezziez handles it from there! Sit back and wait for your prezziez to arrive!</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="faqs">
                                    <div class="card-header" id="headingTwo">
                                        <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwo"><h2 class="mb-0 accordian-heading"><i class="fa fa-plus"></i> What kind of wishlist can I make?</h2></button>
                                    </div>
                                    <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <p>At Prezziez wishlists fall into one of 4 categories</p>
                                                <ul>
                                                    <li><i class="fa fa-circle" aria-hidden="true"></i> <strong>General</strong> <span>- General desired items i.e. clothes, toys, electronics etc. </span></li>
                                                    <li><i class="fa fa-circle" aria-hidden="true"></i> <strong>Registry</strong> <span>- i.e. wedding, honeymoon, baby shower etc.</span></li>
                                                    <li><i class="fa fa-circle" aria-hidden="true"></i> <strong>Experiences</strong> <span>- i.e. vacations, outings, activities, etc.</span></li>
                                                    <li><i class="fa fa-circle" aria-hidden="true"></i> <strong>Events</strong> <span>- i.e. birthday, Christmas, graduation, house warming etc.</span></li>
                                                </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="faqs">
                                    <div class="card-header" id="headingThree">
                                        <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThree"><h2 class="mb-0 accordian-heading"><i class="fa fa-plus"></i> Does Prezziez share my personal information?</h2></button>
                                    </div>
                                    <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <p><i class="fa fa-circle" aria-hidden="true"></i> NO!</p>
                                            <p><i class="fa fa-circle" aria-hidden="true"></i> Prezziez does not reveal the personal information of the giftees or the gifters</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="faqs">
                                    <div class="card-header" id="headingFour">
                                        <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFour"><h2 class="mb-0 accordian-heading"><i class="fa fa-plus"></i> If I am gifting a prezzie will my name be revealed to the gifter?</h2></button>
                                    </div>
                                    <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <p><i class="fa fa-circle" aria-hidden="true"></i> NO!</p>
                                            <p><i class="fa fa-circle" aria-hidden="true"></i> Prezziez allows gifters to gift anonymously. You may enter in any name you would like on the gift note or choose to leave it blank. </p>
                                        </div>
                                    </div>
                                </div>
                                <div class="faqs">
                                    <div class="card-header" id="headingFive">
                                        <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFive"><h2 class="mb-0 accordian-heading"><i class="fa fa-plus"></i> I am a gifter, do I need to make an account to send a prezzie?</h2></button>
                                    </div>
                                    <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <p><i class="fa fa-circle" aria-hidden="true"></i> Not at all! Although encourages, Prezziez does not require gifters to sign up in order to gift.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="faqs">
                                    <div class="card-header" id="headingSix">
                                        <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseSix"><h2 class="mb-0 accordian-heading"><i class="fa fa-plus"></i> What fees does Prezziez charge?</h2></button>
                                    </div>
                                    <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <ul>
                                                <li><i class="fa fa-circle" aria-hidden="true"></i>  Prezziez charges a 10% service fee to gifters on all items and contributions. This is used to cover Prezziez’ operating costs.</li>
                                                <li><i class="fa fa-circle" aria-hidden="true"></i> Prezziez also charges a 3.4% + 30 cent processing fee that goes directly to our payment processor.</li>
                                                <li><i class="fa fa-circle" aria-hidden="true"></i> Fees for items are charged only to the gifter. Giftees do not pay any fees for items they receive.</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="faqs">
                                    <div class="card-header" id="headingSeven">
                                        <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseSeven"><h2 class="mb-0 accordian-heading"><i class="fa fa-plus"></i> What happens if an item a gifter has purchased is no longer available?</h2></button>
                                    </div>
                                    <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <ul>
                                                <li><i class="fa fa-circle" aria-hidden="true"></i>  If for any reason Prezziez is unable to fulfill an order for a giftee, the amount paid for the item (minus processing fees) will be refunded in the form of a Prezziez credit to the giftees’ account.
                                                </li>
                                                <li> i. Note that credits may not be withdrawn for cash
                                                </li>
                                                <li> ii. Also note that an item purchased completely with credits will not incur a service fee.                                             
                                                </li>

                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="faqs">
                                    <div class="card-header" id="headingEight">
                                        <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseEight"><h2 class="mb-0 accordian-heading"><i class="fa fa-plus"></i> What happens if I want to edit an order that a gifter has made for me?</h2></button>
                                    </div>
                                    <div id="collapseEight" class="collapse" aria-labelledby="headingEight" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <ul>
                                                <li><i class="fa fa-circle" aria-hidden="true"></i> Good news! All orders that are in the processing phase may be edited. <span>- Size, color, shipping address etc.</span>
                                                <span>- So long as the change does not affect the total cost of the item you are able to edit it simply by going to the order in question and clicking on “edit”</span>
                                            </li>
                                            <li><i class="fa fa-circle" aria-hidden="true"></i> If an item is in the “order placed” phase, you will not be able to make changes to the order.
                                            </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="faqs">
                                    <div class="card-header" id="headingNine">
                                        <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseNine"><h2 class="mb-0 accordian-heading"><i class="fa fa-plus"></i> Can I put “e” items on my wishlists? i.e. e-giftcards, e-books etc.</h2></button>
                                    </div>
                                    <div id="collapseNine" class="collapse" aria-labelledby="headingNine" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <ul>
                                                 <li><i class="fa fa-circle" aria-hidden="true"></i> Of course you can!</li>
                                                <li><i class="fa fa-circle" aria-hidden="true"></i>   “e” purchases are considered digital purchases and will be sent to the email we have on file for you. Digital purchases are exempt from shipping fees.</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="faqs">
                                    <div class="card-header" id="headingTen">
                                        <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTen"><h2 class="mb-0 accordian-heading"><i class="fa fa-plus"></i> How will I know when I have been gifted a prezzie?</h2></button>
                                    </div>
                                    <div id="collapseTen" class="collapse" aria-labelledby="headingTen" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <ul>
                                                <li><i class="fa fa-circle" aria-hidden="true"></i> You will receive email notification of the prezzie along with an alert on your profile.</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="faqs">
                                    <div class="card-header" id="headingEleven">
                                        <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseEleven"><h2 class="mb-0 accordian-heading"><i class="fa fa-plus"></i> How will I know when a giftee has received the prezzie that I purchased for them?</h2></button>
                                    </div>
                                    <div id="collapseEleven" class="collapse" aria-labelledby="headingEleven" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <ul>
                                            <li><i class="fa fa-circle" aria-hidden="true"></i> When an order has been received by a giftee, they can go into their orders and click “received”
                                            </li>
                                            <li><i class="fa fa-circle" aria-hidden="true"></i> Prezziez will then notify the gifter that their prezzie has been successfully delivered!
                                            </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="faqs">
                                    <div class="card-header" id="headingTwelve">
                                        <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwelve"><h2 class="mb-0 accordian-heading"><i class="fa fa-plus"></i> What happens if a giftee declines my gift offer? Do I receive a refund?</h2></button>
                                    </div>
                                    <div id="collapseTwelve" class="collapse" aria-labelledby="headingTwelve" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <ul>
                                            <li><i class="fa fa-circle" aria-hidden="true"></i> YES!</li>
                                            <li><i class="fa fa-circle" aria-hidden="true"></i> The payment will be returned to your original payment method</li>
                                            <li><i class="fa fa-circle" aria-hidden="true"></i> Note that refunds for declined gift offers will be the total amount spent minus Prezziez service and processing payment fees</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="faqs">
                                    <div class="card-header" id="headingThirteen">
                                        <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseThirteen"><h2 class="mb-0 accordian-heading"><i class="fa fa-plus"></i>  Why does the price of an item increase when I activate the “contribute” feature?</h2></button>
                                    </div>
                                    <div id="collapseThirteen" class="collapse" aria-labelledby="headingThirteen" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <ul>
                                            <li><i class="fa fa-circle" aria-hidden="true"></i>Wishlist items that have the contribution feature activated have some taxes and fees automatically incorporated into the item price. We do this to ensure a more seamless ordering process.</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="faqs">
                                    <div class="card-header" id="headingFourteen">
                                        <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFourteen"><h2 class="mb-0 accordian-heading"><i class="fa fa-plus"></i> What if the price of an item on my Wishlist has changed? Will I be able to update the item?</h2></button>
                                    </div>
                                    <div id="collapseFourteen" class="collapse" aria-labelledby="headingFourteen" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <ul>
                                            <li><i class="fa fa-circle" aria-hidden="true"></i>  Absolutely!</li>
                                            <li><i class="fa fa-circle" aria-hidden="true"></i> You can manually edit the price of the item. Simply click on the item on your wishlist and select “edit”."</li>
                                            <li><i class="fa fa-circle" aria-hidden="true"></i> We recommend that you manually update the price or URL of the items on your list weekly to ensure your items have the most accurate pricing.</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="faqs">
                                    <div class="card-header" id="headingFifteen">
                                        <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseFifteen"><h2 class="mb-0 accordian-heading"><i class="fa fa-plus"></i> Can I be a gifter and giftee?</h2></button>
                                    </div>
                                    <div id="collapseFifteen" class="collapse" aria-labelledby="headingFifteen" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <ul>
                                            <li><i class="fa fa-circle" aria-hidden="true"></i> Of course!</li>
                                            <li><i class="fa fa-circle" aria-hidden="true"></i> You are more than welcome to create wishlists for yourself while also contributing to others’ wishlists. </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="faqs">
                                    <div class="card-header" id="headingSixteen">
                                        <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseSixteen"><h2 class="mb-0 accordian-heading"><i class="fa fa-plus"></i>   What if I want to return a prezzie?</h2></button>
                                    </div>
                                    <div id="collapseSixteen" class="collapse" aria-labelledby="headingSixteen" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <ul>
                                            <li><i class="fa fa-circle" aria-hidden="true"></i> Simply follow the merchant’s returns instructions in the parcel/package you received.</li>
                                            <li><i class="fa fa-circle" aria-hidden="true"></i>  Notify Prezziez that a return is in process, when we receive notification from a merchant that a return has been received you will receive a refund in the form of a Prezziez credit on your account</li>
                                            {{--<li><i class="fa fa-circle" aria-hidden="true"></i> If an item is eligible for return, Prezziez will send you the return label to the email we have on file for you</li>
                                            <li><i class="fa fa-circle" aria-hidden="true"></i> Then simply attach the label and return!</li>--}}
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="faqs">
                                    <div class="card-header" id="headingSeveteen">
                                        <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseSeveteen"><h2 class="mb-0 accordian-heading"><i class="fa fa-plus"></i> What is a comparable merchant?</h2></button>
                                    </div>
                                    <div id="collapseSeveteen" class="collapse" aria-labelledby="headingSeveteen" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <ul>
                                                <li>
                                                    {{--<i class="fa fa-circle" aria-hidden="true"></i>   Choose if you would like to allow Prezziez to fulfill orders for items that are outpriced or out of stock using comparable merchants--}}
                                                    <span><i class="fa fa-circle" aria-hidden="true"></i> For example: Let’s say you have a $15 item from Walmart on your Wishlist that has been purchased for you by a gifter. Upon attempting to fulfill this order, Prezziez sees that the item is either priced higher than the initial purchase price or is no longer available from the desired merchant. The item is however available at Target or Amazon for the same initial price. </span>
                                                    <span><i class="fa fa-circle" aria-hidden="true"></i> By allowing this feature you allow Prezziez to fulfill the order by purchasing the desired item from comparable merchants. Doing this will keep the probability of an order being canceled due to stock inventory or outpricing down.</span>
                                                    <span><i class="fa fa-circle" aria-hidden="true"></i> Prezziez will only ever use comparable merchants. i.e. If the original merchant was Neiman Marcus a comparable merchant would be Bloomingdales. If the original merchant was Ulta a comparable merchant would be Sephora and so forth. </span>
                                                    <span><i class="fa fa-circle" aria-hidden="true"></i> You may opt out of this feature at any time in your settings.</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="faqs">
                                    <div class="card-header" id="headingEighteen">
                                        <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseEighteen"><h2 class="mb-0 accordian-heading"><i class="fa fa-plus"></i> Are there any items that I’m not allowed to have on my wishlist?</h2></button>
                                    </div>
                                    <div id="collapseEighteen" class="collapse" aria-labelledby="headingEighteen" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <ul>
                                                <li><i class="fa fa-circle" aria-hidden="true"></i>   Yes! Giftees are prohibited from adding items that fall under any of the following categories
                                                    <span>- Alcohol</span>
                                                    <span>- Firearms</span>
                                                    <span>- Live animals</span>
                                                    <span>- Tobacco products and certain smoking devices (i.e. Bongs or water pipes)</span>
                                                    <span>- THC items</span>
                                                </li>
                                                <li><i class="fa fa-circle" aria-hidden="true"></i>   Orders that contain prohibited items will not be fulfilled
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="faqs">
                                    <div class="card-header" id="headingNineteen">
                                        <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseNineteen"><h2 class="mb-0 accordian-heading"><i class="fa fa-plus"></i> Why don’t I see my country listed?</h2></button>
                                    </div>
                                    <div id="collapseNineteen" class="collapse" aria-labelledby="headingNineteen" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <ul>
                                                <li><i class="fa fa-circle" aria-hidden="true"></i> Presently only giftees located within the United States can create wishlists on Prezziez.</li>
                                                <li><i class="fa fa-circle" aria-hidden="true"></i> We are a new small business and hope to expand our services to more countries as we grow!</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="faqs">
                                    <div class="card-header" id="headingTwenty">
                                        <button type="button" class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseTwenty"><h2 class="mb-0 accordian-heading"><i class="fa fa-plus"></i> Can I purchase gifts from a wishlist if I am not located in the US?</h2></button>
                                    </div>
                                    <div id="collapseTwenty" class="collapse" aria-labelledby="headingTwenty" data-parent="#accordionExample">
                                        <div class="card-body">
                                            <ul>
                                                <li><i class="fa fa-circle" aria-hidden="true"></i> Absolutely!</li>
                                                <li><i class="fa fa-circle" aria-hidden="true"></i> Although Prezziez only ships out gifts to giftees within the US, anyone from anywhere can purchase a gift so long as they have an accepted payment method.</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12 col-sm-12 col-12">
                    <div class="still-question-text">
                        <h4 class="still-heading">Still have questions?</h4>
                        <p class="still-contact"> Contact us at <a href="mailto:admin@prezziez.com" class="still-link"> admin@prezziez.com</a></p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- FAQ SECTION END -->
@endsection
<!-- FOOTER SECTION END -->
