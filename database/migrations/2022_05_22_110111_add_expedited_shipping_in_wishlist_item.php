<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExpeditedShippingInWishlistItem extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('giftee_wishlist_items', 'expedited_shipping_fee')) {
            Schema::table('giftee_wishlist_items', function (Blueprint $table) {
                $table->decimal('expedited_shipping_fee',16,2)->default(0)->after('shipping_cost');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wishlist_item', function (Blueprint $table) {
            //
        });
    }
}
