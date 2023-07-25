<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateArticleCategoryTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('article_category', function (Blueprint $table) {
            $table->engine = 'Innodb';
            $table->comment('文章分类表');
            $table->integerIncrements('id')->comment('主键');
            $table->integer('pid', false, true)->default(0)->comment('上级分类');
            $table->string('name', 12)->comment('分类名称');
            $table->string('cover', 160)->default('')->comment('分类封面');
            $table->smallInteger('sort', false, true)->default(0)->comment('分类排序');
            $table->tinyInteger('status', false, true)->default(0)->comment('分类状态 0:已关闭 1:已开启');
            $table->timestamp('created_at')->comment('创建时间');
            $table->timestamp('updated_at')->comment('更新时间')->nullable();
            $table->timestamp('deleted_at')->comment('删除时间')->nullable();
            $table->unique('name', 'uk_name');
            $table->index('pid', 'idx_pid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_category');
    }
}
