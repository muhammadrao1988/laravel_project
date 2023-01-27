<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name')->nullable();
                $table->string('displayName')->nullable();
                $table->string('username')->nullable();
                $table->string('userType')->nullable();
                $table->string('prezziesType')->nullable();
                $table->string('email');
                $table->string('contactNumber')->nullable(true);
                $table->enum('alphaRole',['SUPER', 'USERS'])->nullable(false);
                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->string('country')->nullable();
                $table->string('city')->nullable();
                $table->string('state')->nullable();
                $table->string('zip')->nullable();
                $table->text('address')->nullable();
                $table->text('profile_image')->nullable();
                $table->boolean('offer_gift')->default(false)->nullable();
                $table->boolean('fulfill_orders')->default(false)->nullable();
                $table->boolean('privacy_setting')->default(false)->nullable();
                $table->rememberToken();
                $table->boolean('active')->default(1);
                $table->string('timezone')->nullable();
                $table->string('last_login_at')->nullable();
                $table->string('last_login_ip')->nullable();
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
        Schema::dropIfExists('users');
    }
}
