<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddToUserInOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('orders', 'to_user')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->bigInteger('to_user')->nullable()->after('user_id');
            });
        }
        if (!Schema::hasColumn('order_items', 'item_url')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->text('item_url')->nullable()->after('item_name');
            });
        }
        if (!Schema::hasColumn('order_items', 'item_digital_purchase')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->boolean('item_digital_purchase')->default(false)->after('item_url');
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
