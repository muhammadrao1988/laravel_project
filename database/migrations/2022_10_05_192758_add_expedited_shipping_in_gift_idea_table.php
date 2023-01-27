<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExpeditedShippingInGiftIdeaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('giftideas', 'expedited_shipping_fee')) {
            Schema::table('giftideas', function (Blueprint $table) {
                $table->double('expedited_shipping_fee',16,2)->nullable()->after('shipping_fee');
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
        Schema::table('gift_idea', function (Blueprint $table) {
            //
        });
    }
}
