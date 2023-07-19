<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateUploadMediaTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('upload_media', function (Blueprint $table) {
            $table->engine = 'Innodb';
            $table->comment('媒体资源上传');
            $table->bigIncrements('id')->comment('主键');
            $table->string('hash', 32)->comment('资源唯一哈希值');
            $table->string('name', 100)->comment('文件名称');
            $table->string('key', 255)->default('')->comment('文件存储路径');
            $table->string('mime_type', 32)->comment('文件类型');
            $table->integer('size', false, true)->default(0)->comment('文件存储大小');
            $table->string('url', 255)->comment('文件链接');
            $table->string('ext', 10)->default('')->comment('文件后缀');
            $table->string('extra', 500)->default('')->comment('扩展内容, JSON 格式存储');
            $table->string('third_party_hash', 255)->default('')->comment('第三方平台文件资源唯一哈希值');
            $table->string('third_party_uuid', 255)->default('')->comment('第三方平台文件资源 UUID');
            $table->string('third_party', 32)->default('local')->comment('上传平台 local(本地) | qiniu(七牛云)');
            $table->string('bucket', 32)->comment('')->comment('第三方平台文件资源存储仓库');
            $table->timestamp('created_at')->comment('创建时间');
            $table->timestamp('updated_at')->comment('更新时间')->nullable();
            $table->timestamp('deleted_at')->comment('删除时间')->nullable();
            $table->unique(['hash', 'third_party'], 'uk_hash_third');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('upload_media');
    }
}
