<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateArticleExtendTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('article_extend', function (Blueprint $table) {
            $table->engine = 'Innodb';
            $table->comment('文章扩展表');
            $table->bigIncrements('id')->comment('主键');
            $table->bigInteger('article_id', false, true)->comment('文章ID');
            $table->string('source', 32)->default('')->comment('文章来源');
            $table->string('source_url', 160)->default('')->comment('文章来源链接');
            $table->longText('content')->comment('文章正文');
            $table->string('keyword')->default('')->comment('文章关键词');
            $table->text('attachment_path')->comment('文章附件路径')->nullable();
            $table->unique('article_id', 'uqe_article');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_extend');
    }
}
