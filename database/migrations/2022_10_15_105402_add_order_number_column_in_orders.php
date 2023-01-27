<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderNumberColumnInOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('orders', 'order_num')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('order_num')->nullable()->after('id');
            });
        }
        if (!Schema::hasColumn('giftee_wishlist_items', 'order_num')) {
            Schema::table('giftee_wishlist_items', function (Blueprint $table) {
                $table->string('order_num')->nullable()->after('id');
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

    }
}
