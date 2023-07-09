<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateChatbotCategorySceneTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('chatbot_category_scene', function (Blueprint $table) {
            $table->engine = 'Innodb';
            $table->comment('聊天机器人提问场景类型分类');
            $table->bigIncrements('id')->comment('主键');
            $table->string('name', 10)->comment('分类名称');
            $table->bigInteger('pid')->default(0)->comment('父级分类ID');
            $table->string('icon', 30)->default('')->comment('Icon 图标');
            $table->string('describe')->default('')->comment('分类描述');
            $table->string('question')->default('')->comment('提问格式, 二级类目才有, 替换格式类似为 %s% 用来在传递提问时的占位值');
            $table->smallInteger('sort', false, true)->default(0)->comment('分类排序');
            $table->tinyInteger('status')->default(0)->comment('分类状态 0:已关闭 1:已开启');
            $table->timestamp('created_at')->comment('创建时间');
            $table->timestamp('updated_at')->comment('更新时间')->nullable();
            $table->timestamp('deleted_at')->comment('删除时间')->nullable();
            $table->unique(['name', 'pid'], 'uk_name_pid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chatbot_category');
    }
}
