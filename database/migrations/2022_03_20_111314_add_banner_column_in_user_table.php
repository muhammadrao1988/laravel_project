<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBannerColumnInUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('users', 'banner')) {
            Schema::table('users', function (Blueprint $table) {
                $table->text('banner')->nullable()->after('profile_image');
            });
        }
        if (!Schema::hasColumn('users', 'short_description')) {
            Schema::table('users', function (Blueprint $table) {
                $table->text('short_description')->nullable()->after('banner');
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
