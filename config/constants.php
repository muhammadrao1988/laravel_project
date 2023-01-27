<?php

$constants = [];
$banner_height = 350;
$profile_height = 265;
$profile_width = 350;
$banner_width = 1920;
$wishlist_img_width = 425;
$wishlist_img_height = 330;
$constants = [
    'SAVE' => 1,
    'SAVE_ADD_MORE' => 2,
    'UPDATE' => 3,
    'POST_BTN' => 4,
    'IMG_VALIDATION'=>'mimes:jpeg,png,jpg,gif,svg,webp',
    'IMG_VALIDATION_SIZE'=>'max:2048',

    'BANNER_SIZE'=>'dimensions:min-width='.$banner_width.',height='.$banner_height,
    'BANNER_HEIGHT'=>$banner_height,
    'BANNER_WIDTH'=>$banner_width,
    'BANNER_ERR_MSG'=>'The banner image height must be '.$banner_height.' pixel and width at least '.$banner_width.' pixel.',

    'PROFILE_IMG_HEIGHT'=>$profile_height,
    'PROFILE_IMG_WIDTH'=>$profile_width,
    'PROFILE_IMG_SIZE'=>'dimensions:min_width='.$profile_width.',min_height='.$profile_height,
    'PROFILE_IMG_ERR_MSG'=>'The profile image must be at least '.$profile_width.' x '.$profile_height.' pixels',

    'WISHLIST_IMG_SIZE'=>'dimensions:width='.$wishlist_img_width.',height='.$wishlist_img_height,
    'WISHLIST_IMG_ERR_MSG'=>'The image must be '.$wishlist_img_width.' x '.$wishlist_img_height.' pixels',
    'WISHLIST_IMG_HEIGHT'=>$wishlist_img_height,
    'WISHLIST_IMG_WIDTH'=>$wishlist_img_width,

    'PREZZIEZ_FEE' => 0.1,
    'PREZZIEZ_FEE_TEXT' => '10%',
    'PROCESSING_FEE' =>.029 + .30,

    'EXPEDITED_SHIPPING' => 20,

    'PAYMENT_BY_CREDIT'=> 'PrezziezCredit',
    'PAYMENT_BY_STRIPE'=> 'Stripe',
    'PAYMENT_BY_GOOGLE_PAY'=> 'GooglePay',

    'PER_PAGE_LIMIT'=>15,
    'VALID_NAME_VALIDATION'=> '/^[a-zA-Z ]*$/',
    'VALID_URL'=> '/^(http|https)?:\/\/[a-zA-Z0-9-\.]+\.[a-z]{2,4}/',
];

return $constants;
