<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldForContributed extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        if (!Schema::hasColumn('giftee_wishlist_items', 'service_fee')) {
            Schema::table('giftee_wishlist_items', function (Blueprint $table) {
                $table->double('service_fee',16,2)->default(0)->after('expedited_shipping_fee');
            });
        }
        if (!Schema::hasColumn('return_transactions', 'wishlist_item_id')) {
            Schema::table('return_transactions', function (Blueprint $table) {
                $table->bigInteger('wishlist_item_id')->nullable()->after('order_item_id');
            });
        }
        if (!Schema::hasColumn('user_credit_history', 'type')) {
            Schema::table('user_credit_history', function (Blueprint $table) {
                $table->string('type')->default('order')->after('order_id');
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
        //
    }
}
