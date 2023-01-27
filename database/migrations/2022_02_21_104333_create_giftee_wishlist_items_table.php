<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGifteeWishlistItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('giftee_wishlist_items')) {
            Schema::create('giftee_wishlist_items', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->bigInteger('giftee_wishlist_id')->nullable();
                $table->string('type')->nullable()->comment("manual or url");
                $table->text('url')->nullable();
                $table->text('gift_name')->nullable();
                $table->double('price',16,2)->nullable();
                $table->double('total_price',16,2)->nullable();
                $table->double('collected_amount',16,2)->nullable();
                $table->integer('quantity')->nullable();
                $table->string('merchant')->nullable();
                $table->boolean('accept_donation')->default(0);
                $table->boolean('digital_purchase')->default(0);
                $table->text('picture')->nullable();
                $table->text('gift_details')->nullable();
                $table->integer('user_id')->nullable();
                $table->string('status')->nullable();
                $table->string('purchasing_status')->nullable();
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
        Schema::dropIfExists('giftee_wishlist_items');
    }
}
