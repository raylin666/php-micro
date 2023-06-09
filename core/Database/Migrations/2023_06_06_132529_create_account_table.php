<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateAccountTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('account', function (Blueprint $table) {
            $table->engine = 'Innodb';
            $table->comment('系统管理账号');
            $table->bigIncrements('id')->comment('主键');
            $table->string('username', 20)->comment('账号名称');
            $table->string('password', 60)->comment('账号密码');
            $table->string('real_username', 20)->comment('真实姓名');
            $table->tinyInteger('sex')->default(0)->comment('性别 0未知 1男 2女');
            $table->dateTime('birthday_at')->comment('出生时间')->nullable();
            $table->string('avatar', 160)->default('')->comment('账号头像');
            $table->string('phone_area', 10)->default('0086')->comment('手机区号');
            $table->string('phone', 20)->default('')->comment('手机号码');
            $table->string('email', 36)->default('')->comment('电子邮箱');
            $table->string('current_login_token')->default('')->comment('当前最新登录TOKEN (可作为单点唯一登录标识)');
            $table->integer('last_login_ip', false, true)->default(0)->comment('最后登录IP');
            $table->tinyInteger('status')->default(0)->comment('账号状态 0关闭 1开启 2冻结');
            $table->timestamp('first_login_at')->comment('首次登录时间')->nullable();
            $table->timestamp('last_login_at')->comment('最后登录时间')->nullable();
            $table->timestamp('created_at')->comment('创建时间');
            $table->timestamp('updated_at')->comment('更新时间')->nullable();
            $table->timestamp('deleted_at')->comment('删除时间')->nullable();
            $table->unique('username', 'uk_username');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account');
    }
}
