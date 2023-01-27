<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnInGiftIdea extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('giftideas', 'shipping_fee')) {
            Schema::table('giftideas', function (Blueprint $table) {
                $table->double('shipping_fee',16,2)->nullable()->after('price');
            });
        }
        if (!Schema::hasColumn('giftideas', 'merchant_name')) {
            Schema::table('giftideas', function (Blueprint $table) {
                $table->string('merchant_name')->nullable()->after('merchant');
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
