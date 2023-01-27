<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRetrunTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('return_transactions')) {
            Schema::create('return_transactions', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('order_id')->nullable();
                $table->bigInteger('order_item_id')->nullable();
                $table->text('previous_charge_id')->nullable();
                $table->text('return_id')->nullable();
                $table->text('balance_transaction')->nullable();
                $table->text('payment_intent')->nullable();
                $table->double('subtotal', 16, 2)->nullable();
                $table->double('shipping_fee', 16, 2)->nullable();
                $table->double('credit_apply', 16, 2)->nullable();
                $table->double('return_amount', 16, 2)->nullable();
                $table->boolean('active')->default(1);
                $table->string('created_by')->nullable();
                $table->string('updated_by')->nullable();
                $table->string('deleted_by')->nullable();
                $table->softDeletes();
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
