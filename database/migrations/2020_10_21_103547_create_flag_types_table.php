<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlagTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	if (!Schema::hasTable('flag_types')) {
	        Schema::create('flag_types', function (Blueprint $table) {
	            $table->id();
	            $table->string("name");
	            $table->string("code");
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
