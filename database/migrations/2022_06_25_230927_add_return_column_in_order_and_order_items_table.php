<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReturnColumnInOrderAndOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('orders', 'return_status')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->tinyInteger('return_status')->default(0)->after('payment_id');
            });
        }
        if (!Schema::hasColumn('order_items', 'return_status_item')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->tinyInteger('return_status_item')->default(0)->after('item_status');
            });
        }
        if (!Schema::hasColumn('order_items', 'return_description_giftee')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->text('return_description_giftee')->nullable()->after('return_status_item');
            });
        }
        if (!Schema::hasColumn('order_items', 'return_description_admin')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->text('return_description_admin')->nullable()->after('return_description_giftee');
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
        Schema::table('order_and_order_items', function (Blueprint $table) {
            //
        });
    }
}
