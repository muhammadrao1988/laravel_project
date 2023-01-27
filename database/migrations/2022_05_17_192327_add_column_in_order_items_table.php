<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnInOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('order_items', 'item_name')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->string('item_name')->nullable()->after('wishlist_item_id');
            });
        }

        if (!Schema::hasColumn('order_items', 'item_image')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->text('item_image')->nullable()->after('item_name');
            });
        }

        if (!Schema::hasColumn('order_items', 'item_price')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->decimal('item_price',16,2)->default(0)->after('item_image');
            });
        }

        if (!Schema::hasColumn('order_items', 'item_qty')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->integer('item_qty')->default(0)->after('item_price');
            });
        }

        if (!Schema::hasColumn('order_items', 'item_shipping_price')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->decimal('item_shipping_price',16,2)->default(0)->after('item_qty');
            });
        }
        if (!Schema::hasColumn('order_items', 'item_expedited_shipping')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->boolean('item_expedited_shipping')->default(false)->after('item_shipping_price');
            });
        }
        if (!Schema::hasColumn('order_items', 'item_expedited_shipping_price')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->decimal('item_expedited_shipping_price',16,2)->default(0)->after('item_expedited_shipping');
            });
        }


        if (!Schema::hasColumn('order_items', 'item_detail')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->text('item_detail')->nullable()->after('item_expedited_shipping_price');
            });
        }

        if (!Schema::hasColumn('order_items', 'item_merchant')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->text('item_merchant')->nullable()->after('item_detail');
            });
        }


        if (!Schema::hasColumn('order_items', 'active')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->boolean('active')->default(1)->after('item_merchant');
            });
        }

        if (!Schema::hasColumn('order_items', 'created_by')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->string('created_by')->nullable()->after('active');
            });
        }

        if (!Schema::hasColumn('order_items', 'updated_by')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->string('updated_by')->nullable()->after('created_by');
            });
        }

        if (!Schema::hasColumn('order_items', 'deleted_by')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->string('deleted_by')->nullable()->after('updated_by');
            });
        }

        if (!Schema::hasColumn('order_items', 'deleted_at')) {
            Schema::table('order_items', function (Blueprint $table) {
                $table->timestamp('deleted_at')->nullable()->after('deleted_by');
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
