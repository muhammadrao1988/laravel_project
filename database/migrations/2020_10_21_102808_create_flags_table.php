<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFlagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	if (!Schema::hasTable('flags')) {
	        Schema::create('flags', function (Blueprint $table) {
	            $table->id();
	            $table->string("flagType");
	            $table->bigInteger("parentId")->default(0);
	            $table->string("name");
	            $table->string("description")->nullable(true);
	            $table->string("field1")->nullable(true);
	            $table->string("field2")->nullable(true);
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
