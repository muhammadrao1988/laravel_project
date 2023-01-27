<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnInStates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('states', 'tax_rate')) {
            Schema::table('states', function (Blueprint $table) {
                $table->double('tax_rate',16,2)->default(0)->after('status');
            });
        }
        if (!Schema::hasColumn('states', 'active')) {
            Schema::table('states', function (Blueprint $table) {
                $table->boolean('active')->default(1)->after('tax_rate');
            });
        }
        if (!Schema::hasColumn('states', 'created_by')) {
            Schema::table('states', function (Blueprint $table) {
                $table->string('created_by')->nullable()->after('active');
            });
        }
        if (!Schema::hasColumn('states', 'updated_by')) {
            Schema::table('states', function (Blueprint $table) {
                $table->string('updated_by')->nullable()->after('created_by');
            });
        }
        if (!Schema::hasColumn('states', 'deleted_by')) {
            Schema::table('states', function (Blueprint $table) {
                $table->string('deleted_by')->nullable()->after('updated_by');
            });
        }
        if (!Schema::hasColumn('giftee_wishlist_items', 'tax_rate')) {
            Schema::table('giftee_wishlist_items', function (Blueprint $table) {
                $table->double('tax_rate',16,2)->nullable()->after('status');
            });
        }
        if (Schema::hasColumn('states', 'status')) {
            Schema::table('states', function($table) {
                $table->dropColumn(['status']);
            });
        }
        if (Schema::hasColumn('states', 'country_id')) {
            Schema::table('states', function($table) {
                $table->dropForeign('states_country_id_foreign');
                $table->dropColumn(['country_id']);
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
