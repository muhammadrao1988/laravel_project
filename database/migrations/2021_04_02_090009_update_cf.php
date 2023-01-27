<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateCf extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('custom_field_responses', 'fieldId')) {
            Schema::table('custom_field_responses', function($table) {
                $table->dropColumn(['fieldId']);
            });
        }

        if (Schema::hasColumn('custom_field_responses', 'value')) {
            Schema::table('custom_field_responses', function($table) {
                $table->dropColumn(['value']);
            });
        }

        if (!Schema::hasColumn('custom_field_responses', 'response')) {
            Schema::table('custom_field_responses', function (Blueprint $table) {
                $table->text('response')->nullable()->after('modelId');
            });
        }

        if (!Schema::hasColumn('custom_field_responses', 'model')) {
            Schema::table('custom_field_responses', function (Blueprint $table) {
                $table->string('model')->after('id');
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
        //
    }
}
