<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('orders')) {
            Schema::create('orders', function (Blueprint $table) {
                $table->id();
                $table->bigInteger('customer_id')->nullable();
                $table->string('giftee_username')->nullable();
                $table->double('total_amount', 16, 2)->nullable();
                $table->string('status')->default("Order Processing");
                $table->tinyInteger('surprise');
                $table->string('payment_method');
                $table->string('note')->nullable();
                $table->tinyInteger('recieve_purchase_status')->nullable();
                $table->string('payment_id');
                $table->timestamps();
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
