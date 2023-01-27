<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnInReturnTransaction extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('return_transactions', 'taxes')) {
            Schema::table('return_transactions', function (Blueprint $table) {
                $table->double('taxes',16,2)->nullable()->after('shipping_fee');
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
        Schema::table('return_transaction', function (Blueprint $table) {
            //
        });
    }
}
