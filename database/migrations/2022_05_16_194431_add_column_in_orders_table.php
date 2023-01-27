<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnInOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('orders', 'subtotal')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->decimal('subtotal',16,2)->default(0)->after('giftee_username');
            });
        }
        if (!Schema::hasColumn('orders', 'expedited_shipping')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->boolean('expedited_shipping')->default(false)->after('subtotal');
            });
        }
        if (!Schema::hasColumn('orders', 'expedited_shipping_fee')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->decimal('expedited_shipping_fee',16,2)->default(0)->after('expedited_shipping');
            });
        }
        if (!Schema::hasColumn('orders', 'shipping_fee')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->decimal('shipping_fee',16,2)->default(0)->after('expedited_shipping_fee');
            });
        }
        if (!Schema::hasColumn('orders', 'processing_fee')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->decimal('processing_fee',16,2)->default(0)->after('shipping_fee');
            });
        }
        if (!Schema::hasColumn('orders', 'use_credit')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->boolean('use_credit')->default(false)->after('processing_fee');
            });
        }
        if (!Schema::hasColumn('orders', 'credit_apply')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->decimal('credit_apply',16,2)->default(0)->after('use_credit');
            });
        }

        if (!Schema::hasColumn('orders', 'order_type')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('order_type')->nullable()->after('id');
            });
        }
        if (!Schema::hasColumn('orders', 'billing_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->integer('billing_id')->nullable()->after('order_type');
            });
        }

        if (!Schema::hasColumn('orders', 'user_id')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->integer('user_id')->nullable()->after('billing_id');
            });
        }

        if (Schema::hasColumn('orders', 'customer_id')) {
            Schema::table('orders', function($table) {
                $table->dropColumn(['customer_id']);
            });
        }

        if (!Schema::hasColumn('orders', 'active')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->boolean('active')->default(1)->after('payment_id');
            });
        }

        if (!Schema::hasColumn('orders', 'created_by')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('created_by')->nullable()->after('active');
            });
        }

        if (!Schema::hasColumn('orders', 'updated_by')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('updated_by')->nullable()->after('created_by');
            });
        }

        if (!Schema::hasColumn('orders', 'deleted_by')) {
            Schema::table('orders', function (Blueprint $table) {
                $table->string('deleted_by')->nullable()->after('updated_by');
            });
        }

        if (!Schema::hasColumn('orders', 'deleted_at')) {
            Schema::table('orders', function (Blueprint $table) {
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
