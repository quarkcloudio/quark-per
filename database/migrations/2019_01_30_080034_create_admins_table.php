<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('username',20)->unique();
            $table->string('nickname');
            $table->string('email',50)->unique();
            $table->string('phone',11)->unique();
            $table->tinyInteger('sex')->default(1);
            $table->string('password');
            $table->string('avatar')->nullable();
            $table->string('wechat_openid')->nullable()->default('');
            $table->string('wechat_unionid')->nullable()->default('');
            $table->string('qq_openid')->nullable()->default('');
            $table->string('weibo_uid')->nullable()->default('');
            $table->string('last_login_ip')->nullable()->default('');
            $table->timestamp('last_login_time')->nullable();
            $table->boolean('status')->default(1);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
