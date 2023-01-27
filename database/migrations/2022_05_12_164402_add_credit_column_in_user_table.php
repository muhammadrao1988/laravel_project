<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCreditColumnInUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('users', 'credit')) {
            Schema::table('users', function (Blueprint $table) {
                $table->double('credit',16,2)->default(0)->after('email');
            });
        }
    }
}
