<?php

use App\Mail\FollowOperation;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('cart', 'Frontend\Cart\ManageCartController@index')->name('cart');
Route::get('stripes', 'Frontend\Cart\ManageCartController@stripePost')->name('stripe.post');
Route::post('checkout', 'Frontend\Cart\ManageCartController@checkout')->name('checkout');
Route::get('checkout', 'Frontend\Cart\ManageCartController@checkout')->name('checkout_get');
Route::post('remove-from-cart', 'Frontend\Cart\ManageCartController@remove')->name('remove.from.cart');
Route::post('remove-selected-from-cart', 'Frontend\Cart\ManageCartController@removeSelectedItems')->name('remove-selected-from-cart');
Route::post('stripe', 'Frontend\Cart\ManageCartController@payWithStripe')->name('stripe-pay');
Route::post('payment', 'Frontend\Cart\ManageCartController@payment')->name('payment');
Route::get('success','Frontend\Cart\ManageCartController@success')->name('success');
Route::get('cancel','Frontend\Cart\ManageCartController@success')->name('cancel');
Route::get('home',function(){
    return view('frontend.pages.home');
})->name('home');
Route::post('billing-address', 'Frontend\Cart\ManageCartController@payWithStripe')->name('billing-address');
Route::get('/', 'Frontend\Pages\ManagePagesController@index')->name('front');
Route::get('/contact-us', 'Frontend\Pages\ManagePagesController@staticPage')->name('contact-us');
Route::get('/faq', 'Frontend\Pages\ManagePagesController@staticPage')->name('faq');
Route::get('/about-us', 'Frontend\Pages\ManagePagesController@staticPage')->name('about-us');
Route::get('/privacy-policy', 'Frontend\Pages\ManagePagesController@staticPage')->name('privacy-policy');
Route::get('/terms-condition', 'Frontend\Pages\ManagePagesController@staticPage')->name('terms-condition');
Route::get('contributed-checkout','Frontend\Contribution\ManageContributionController@index')->name('contributed-checkout');
Route::get('contributed-cart-add','Frontend\Contribution\ManageContributionController@addToCart')->name('contributed-cart-add');
Route::post('contribute','Frontend\Contribution\ManageContributionController@contribute')->name('contribute');
Route::post('gift-offer-cart-add','Frontend\GiftOffer\ManageGiftOfferController@addToCart')->name('gift-offer-cart-add');
Route::get('gift-offer-checkout','Frontend\GiftOffer\ManageGiftOfferController@index')->name('gift-offer-checkout');
Route::post('/ajaxCall', 'Frontend\Pages\ManagePagesController@ajaxCall')->name('ajaxCall');
Route::get('giftguide','Frontend\GiftIdea\ManageGiftIdeaController@showGiftIdeas')->name('giftideas');
Route::get('/getgiftideas','Frontend\GiftIdea\ManageGiftIdeaController@getgiftideas')->name('getgiftideas');
Route::get('/howitworks','Frontend\GiftIdea\ManageGiftIdeaController@gethowitworks')->name('howitworks');

Route::get('webhookServiceAddress',function(){
    \App\Models\Address::syncAddresses();
    dd("synced");
});
Route::post('webhookServiceAddress',function(){
    \App\Models\Address::syncAddresses();
    dd("synced");
})->name('webhookServiceAddress');

Route::prefix('admin')->group(function() {
    Route::get('/login', 'Admin\Auth\LoginController@showLoginForm')->name('admin.login');
    Route::post('/login', 'Admin\Auth\LoginController@login')->name('admin.login.submit');
    Route::post('/logout', 'Admin\Auth\LoginController@logout')->name('admin.logout');

    // Password Reset Routes...
    Route::get('password/reset', 'Admin\Auth\ForgotPasswordController@showLinkRequestForm')->name('admin.password.request');
    Route::post('password/email', 'Admin\Auth\ForgotPasswordController@sendResetLinkEmail')->name('admin.password.email');
    Route::get('password/reset/{token}', 'Admin\Auth\ResetPasswordController@showResetForm')->name('admin.password.reset');
    Route::post('password/reset', 'Admin\Auth\ResetPasswordController@reset')->name('admin.password.verify');
   /* Route::get('/login', 'Auth\LoginController@showLoginForm')->name('admin.login');
    Route::post('/login', 'Auth\LoginController@login')->name('admin.login.submit');
    Route::post('/logout', 'Auth\LoginController@logout')->name('admin.logout');*/

});

Route::middleware(['admin.auth'])->prefix('admin')->namespace('Admin')->group(function () {
    Route::get('/{url?}', 'Dashboard\ManageDashboardController@index')->where('url','home|dashboard|')->name('admin.dashboard');
    //Route::get('dashboard', 'Dashboard\ManageDashboardController@index')->name('dashboard');
    //Route::get('home', 'Dashboard\ManageDashboardController@index');
    //Route::post('dashboard', 'Dashboard\ManageDashboardController@index')->name('dashboard');
    Route::post('select2Ajax', function() { Common::select2Ajax(); })->name('select2Ajax');
});
Route::middleware(['admin.auth', 'permissions'])->namespace('Admin')->prefix('admin')->group(function () {
    Route::resource('users', 'Users\ManageUsersController');
    Route::resource('configuration', 'Configuration\ManageConfigurationController');
    Route::resource('roles', 'Users\RolesController');
    Route::match(['put', 'patch'], 'roles/permissions/assign/{role}', 'Users\RolesController@assignPermission')->name('roles.assignPermission');
    Route::resource('flagtype', 'FlagType\ManageFlagTypeController');
    Route::resource('flag', 'Flag\ManageFlagController');
    Route::post('flag/child','Flag\ManageFlagController@getFlagChild')->name('flag.child');
    Route::resource('cronJob', 'CronJob\ManageCronJobController');
    Route::get('sample/{model}', 'File\ManageFileController@sample')->name('sample');
    Route::post('import/{model}', 'File\ManageFileController@import')->name('import');
    Route::get('export/{model}', 'File\ManageFileController@export')->name('export');
    Route::get('download/{model}', 'File\ManageFileController@download')->name('download');
    Route::resource('menu', 'Menu\ManageMenuController');
    Route::resource('customfield', 'CustomField\ManageCustomFieldController');
    Route::resource('notification', 'Notification\ManageNotificationController');
    Route::resource('services', 'Service\ManageServiceController');
    Route::resource('addresses', 'Address\ManageAddressController');
    Route::resource('accountGroups', 'AccountGroup\ManageAccountGroupController');
    Route::resource('accountTypes', 'AccountType\ManageAccountTypeController');
    Route::resource('giftee', 'Giftee\ManageGifteeController');
    Route::resource('giftguide', 'GiftIdeas\ManageGiftIdeasController');
    Route::resource('categories', 'Categories\ManageCategoriesController');
    Route::resource('states', 'States\ManageStateController');
    Route::resource('cart_orders', 'Orders\ManageCartOrdersController');
    Route::resource('contribution_orders', 'Orders\ManageContributionOrdersController');
    Route::resource('gift_offer_orders', 'Orders\ManageGiftOfferOrdersController');

    Route::post('gift_offer_cancelled', 'Orders\ManageGiftOfferOrdersController@cancelledOrder')->name('giftOfferCancelled');
    Route::post('gift_offer_order_num_update', 'Orders\ManageGiftOfferOrdersController@updateOrderNum')->name('giftOfferOrderNumUpdate');
    Route::post('gift_offer_status_manage', 'Orders\ManageGiftOfferOrdersController@manageGiftOfferStatus')->name('manageGiftOfferStatus');
    Route::post('gift_offer_returned', 'Orders\ManageGiftOfferOrdersController@manageGiftOffersReturned')->name('manageGiftOffersReturned');

    Route::post('cart_item_cancelled', 'Orders\ManageCartOrdersController@cancelledOrder')->name('cartItemCancelled');
    Route::post('cart_item_order_num_update', 'Orders\ManageCartOrdersController@updateOrderNum')->name('cartItemOrderNumUpdate');
    Route::post('cart_item_status_manage', 'Orders\ManageCartOrdersController@manageCartItemStatus')->name('manageCartItemStatus');
    Route::post('cart_item_returned', 'Orders\ManageCartOrdersController@manageCartItemReturned')->name('manageCartItemsReturned');

    Route::post('contributed_cancelled', 'Orders\ManageContributionOrdersController@cancelledOrder')->name('contributedCancelled');
    Route::post('contributed_order_num_update', 'Orders\ManageContributionOrdersController@updateOrderNum')->name('contributedOrderNumUpdate');
    Route::post('contributed_status_manage', 'Orders\ManageContributionOrdersController@manageContributedItemStatus')->name('manageContributedItemStatus');
    Route::post('contributed_returned', 'Orders\ManageContributionOrdersController@manageContributedReturned')->name('manageContributedReturned');


    /*Change Password*/
    Route::get('changePassword', 'Users\ChangePasswordController@index')->middleware('auth')->name('change-password');
    Route::post('changePassword', 'Users\ChangePasswordController@store')->middleware('auth')->name('update-password');
    Route::get('order-logs/{id}','Orders\ManageOrdersController@log')->name('order-logs');
    /**/
});

Route::group([
    'namespace' => 'Auth',
], function () {

    // Authentication Routes...
    Route::get('login', 'WebsiteLoginController@showLoginForm')->name('login_page');
    Route::post('login', 'WebsiteLoginController@login')->name('login');
    Route::get('logout', 'WebsiteLoginController@logout')->name('logout');

    // Registration Routes...
    Route::get('register', 'RegisterController@showRegistrationForm')->name('register_page');
    Route::post('register', 'RegisterController@register')->name('register');
    Route::get('register/activate/{token}', 'RegisterController@confirm')->name('email_confirm');

    // Password Reset Routes...
    Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/reset', 'ResetPasswordController@reset');

});
Route::middleware(['website.auth'])->group(function () {

    Route::patch('update-cart', 'Frontend\Cart\ManageCartController@update')->name('update.cart');
// Route::delete('remove-from-cart', 'Frontend\Cart\ManageCartController@remove')->name('remove.from.cart');
    Route::get('account_settings', 'Frontend\MyAccount\ManageMyAccountController@index')->name('myaccount');
    Route::post('myaccount', 'Frontend\MyAccount\ManageMyAccountController@index')->name('myaccountSubmit');
    Route::post('delete-service', 'Frontend\MyAccount\ManageMyAccountController@removeService')->name('remove.service');
    Route::post('add-wishlist', 'Frontend\WishList\ManageWishListController@saveWishList')->name('add.wishlist');
    Route::post('remove-wishlist', 'Frontend\WishList\ManageWishListController@removeWishList')->name('remove.wishlist');
    Route::post('add-wishlist-item', 'Frontend\WishList\ManageWishListController@saveWishListItem')->name('add.wishlist.item');
    Route::post('add-to-wishlist', 'Frontend\WishList\ManageWishListController@addToWishlist')->name('add.towishlist');
    Route::post('delete-wishlist-item', 'Frontend\WishList\ManageWishListController@deleteWishListItem')->name('delete.wishlist.item');
    Route::post('change-profile-banner', 'Frontend\MyAccount\ManageMyAccountController@changeProfileBanner')->name('change-profile-banner');
    Route::post('remove-profile-banner', 'Frontend\MyAccount\ManageMyAccountController@removeProfileBanner')->name('remove-profile-banner');
    Route::post('remove-profile-image', 'Frontend\MyAccount\ManageMyAccountController@removeProfileImage')->name('remove-profile-image');
    Route::get('wishlist_item/{id}', 'Frontend\WishList\ManageWishListController@showWishListItemDetail')->name('show.wishlist.item.detail');
    Route::get('following', 'Frontend\MyAccount\ManageFollowController@following')->name('following');
    Route::get('followers', 'Frontend\MyAccount\ManageFollowController@followers')->name('followers');
    Route::post('follow_operation', 'Frontend\MyAccount\ManageFollowController@follow_operation')->name('follow_operation');
    Route::get('/notifications','Frontend\Notification\ManageNotificationController@index')->name('notifications');
    Route::post('/read_notification','Frontend\Notification\ManageNotificationController@read_notification')->name('notification.read');


    Route::post('cancel-order/{orderId}','Frontend\Order\ManageOrderController@cancelOrder')->name('cancel-order');
    // Route::get('order/items/{orderId}','Frontend\Order\ManageOrderController@getOrderItems')->name('get-order-items');
    Route::get('gift-offers','Frontend\GiftOffer\ManageGiftOfferController@giftOffer')->name('giftOffer');
    Route::get('gift-offer-detail/{id}','Frontend\GiftOffer\ManageGiftOfferController@giftOfferDetail')->name('giftOfferDetail');
    Route::post('gift-offer-confirmation','Frontend\GiftOffer\ManageGiftOfferController@giftOfferConfirmation')->name('giftOfferConfirmation');
    Route::post('gift-offer-status_manage', 'Frontend\GiftOffer\ManageGiftOfferController@manageGiftOfferReceivedStatus')->name('manageGiftOfferReceivedStatus');
    Route::post('gift-offer-returned', 'Frontend\GiftOffer\ManageGiftOfferController@manageGiftOfferReturned')->name('manageGiftOfferReturned');
    Route::post('wish-list-status_manage', 'Frontend\WishList\ManageWishListController@manageWishListReceivedStatus')->name('manageWishListReceivedStatus');
    Route::post('wish-list-returned', 'Frontend\WishList\ManageWishListController@manageWishListReturned')->name('manageWishListReturned');
    Route::get('my_orders', 'Frontend\Order\ManageOrderController@index')->name('my-orders');
    Route::get('my_orders/{id}','Frontend\Order\ManageOrderController@show')->name('my-orders.show');

    Route::get('credits','Frontend\MyAccount\ManageMyAccountController@credits')->name('credits');

});
/*API*/
Route::post('API', 'API\ManageAPIController@index');


Route::get('migrate',function(){
    Artisan::call('migrate');
    dd("migrated");
});

Route::get('seed',function(){
    Artisan::call('db:seed --class='.request()->get('class'));
    dd("seeded");
});
Route::get('freshmigrate',function(){
    Artisan::call('migrate:fresh --seed');
});
Route::get('storage_link',function(){
    Artisan::call('storage:link');
    echo "done";
});
Route::get('clear_config',function(){
    Artisan::call('view:clear');
    Artisan::call('config:cache');
    Artisan::call('config:clear');
    echo "done";
});
Route::get('wishlist/{id}', 'Frontend\WishList\ManageWishListController@showWishListItems')->name('show.wishlist.item');
/**/
Route::get('get-giftees','Frontend\Pages\ManagePagesController@getGiftees')->name('get-giftees');

// add to cart
Route::get('add-to-cart/{id}','Frontend\Cart\ManageCartController@addToCart')->name('add.to.cart');
Route::get('/mailable', function () {

    $msg = "Dear Muhammad,";
    $custom_html = "<table><tr><td>Name</td><td>Fer</td></tr></table>";
    $body = [
        'msg'=>$msg,
        'url'=> "#",
        'btn_text'=>"View Order Detail",
        'subject' => "test email",
        'module' => "OrderItem",
        //'send_to' => "muhammadrao1988@gmail.com",
        'custom_html'=>$custom_html,
        'send_to' => "giftee@yopmail.com",
    ];

    return new App\Mail\FollowOperation($body);
});
Route::post('update-profile-image','Frontend\MyAccount\ManageMyAccountController@storeUserImage')->name('update-profile-image');
Route::get("/{username}",'Frontend\MyAccount\ManageMyAccountController@profileData')->name('profileUrl');


