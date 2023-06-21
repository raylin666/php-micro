<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateArticleTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('article', function (Blueprint $table) {
            $table->engine = 'Innodb';
            $table->comment('文章表');
            $table->bigIncrements('id')->comment('主键');
            $table->string('title', 32)->comment('文章标题');
            $table->string('author', 20)->default('')->comment('文章作者');
            $table->string('summary', 140)->default('')->comment('文章摘要');
            $table->string('cover', 160)->default('')->comment('文章封面图片');
            $table->smallInteger('sort', false, true)->default(0)->comment('文章排序');
            $table->tinyInteger('recommend_flag')->default(0)->comment('文章推荐标识 0:未推荐 1:已推荐');
            $table->tinyInteger('commented_flag')->default(1)->comment('文章是否允许评论 0:不允许 1:允许');
            $table->tinyInteger('status')->default(0)->comment('文章状态 0:已关闭 1:已开启');
            $table->integer('view_count', false, true)->default(0)->comment('文章浏览量');
            $table->integer('comment_count', false, true)->default(0)->comment('文章评论数');
            $table->integer('collection_count', false, true)->default(0)->comment('文章收藏量');
            $table->integer('zan_count', false, true)->default(0)->comment('文章点赞数');
            $table->integer('share_count', false, true)->default(0)->comment('文章分享数');
            $table->bigInteger('user_id', false, true)->default(0)->comment('发布者编号ID');
            $table->timestamp('last_commented_at')->comment('最新评论时间')->nullable();
            $table->timestamp('created_at')->comment('创建时间');
            $table->timestamp('updated_at')->comment('更新时间')->nullable();
            $table->timestamp('deleted_at')->comment('删除时间')->nullable();
            $table->index('user_id', 'idx_user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article');
    }
}
