<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsStatusTimelineTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('exception_transactions')) {
            Schema::create('order_items_status_timeline', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('order_id')->nullable();
                $table->bigInteger('order_item_id')->nullable();
                $table->bigInteger('wishlist_item_id')->nullable();
                $table->string('status')->nullable();
                $table->text('description')->nullable();
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
