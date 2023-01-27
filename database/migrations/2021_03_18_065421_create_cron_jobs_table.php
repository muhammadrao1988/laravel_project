<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCronJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('cron_jobs')) {
            Schema::create('cron_jobs', function (Blueprint $table) {
                $table->id();
                $table->string('function')->nullable();
                $table->string('function_arg_1')->nullable();
                $table->string('function_arg_2')->nullable();
                $table->string('frequency_func')->nullable();
                $table->string('frequency_func_arg_1')->nullable();
                $table->string('frequency_func_arg_2')->nullable();
                $table->string('model_type')->nullable();
                $table->bigInteger('model_id')->nullable();
                $table->bigInteger('total_execution')->nullable();
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
