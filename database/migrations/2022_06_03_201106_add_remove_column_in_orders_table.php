<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRemoveColumnInOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('orders', 'expedited_shipping_fee')) {
            Schema::table('orders', function($table) {
                $table->dropColumn(['expedited_shipping_fee']);
            });
        }
        if (!Schema::hasColumn('order_items', 'rejected_returned_reason')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->text('rejected_returned_reason')->nullable()->after('item_merchant');
            });
        }
        if (!Schema::hasColumn('order_items', 'giftee_item_specification')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->text('giftee_item_specification')->nullable()->after('rejected_returned_reason');
            });
        }
        if (!Schema::hasColumn('order_items', 'item_status')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->string('item_status')->default('Order Processing')->after('giftee_item_specification');
            });
        }
        if (!Schema::hasColumn('orders', 'wishlist_item_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->bigInteger('wishlist_item_id')->nullable()->after('order_type');
            });
        }
        if (!Schema::hasColumn('orders', 'prezziez_fee')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->double('prezziez_fee',16,2)->nullable()->after('total_amount');
            });
        }
        if (!Schema::hasColumn('orders', 'payment_processing_fee')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->double('payment_processing_fee',16,2)->nullable()->after('prezziez_fee');
            });
        }
        if (!Schema::hasColumn('orders', 'taxes')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->double('taxes',16,2)->nullable()->after('payment_processing_fee');
            });
        }
        if (!Schema::hasColumn('order_status_timeline', 'type')) {
            Schema::table('order_status_timeline', function (Blueprint $table) {
                $table->string('type')->default('order')->after('id');
            });
        }
        if (!Schema::hasColumn('orders', 'order_step')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->smallInteger('order_step')->default(1)->after('status');
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
