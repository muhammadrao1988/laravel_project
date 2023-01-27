<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExceptionTransctionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('exception_transactions')) {
            Schema::create('exception_transactions', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->integer('order_id')->nullable();
                $table->integer('user_id')->nullable();
                $table->integer('billing_id')->nullable();
                $table->text('transaction_id')->nullable();
                $table->text('description')->nullable();
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
