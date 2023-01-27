<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomFieldsTables extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('custom_fields')) {
            Schema::create('custom_fields', function (Blueprint $table) {
                $table->increments('id');
                $table->string('model');
                $table->string('title');
                $table->string('name');
                $table->string('description')->nullable();
                $table->string('type');
                $table->string('rules')->nullable();
                $table->string('acceptable')->nullable();
                $table->string('defaultValue')->nullable();
                $table->integer('sort')->default(0);
                $table->boolean('active')->default(1);
                $table->string('entryStatus', 10)->default('Web')->nullable(true);
                $table->string('created_by')->nullable();
                $table->string('updated_by')->nullable();
                $table->string('deleted_by')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('custom_field_responses')) {
            Schema::create('custom_field_responses', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedInteger('fieldId');
                $table->unsignedInteger('modelId');
                $table->string('value')->nullable();
                $table->boolean('active')->default(1);
                $table->string('entryStatus', 10)->default('Web')->nullable(true);
                $table->string('created_by')->nullable();
                $table->string('updated_by')->nullable();
                $table->string('deleted_by')->nullable();
                $table->softDeletes();
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        // 
    }
}
