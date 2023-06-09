<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

class CreateArticleCategoryRelationTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('article_category_relation', function (Blueprint $table) {
            $table->engine = 'Innodb';
            $table->comment('文章分类关系表');
            $table->bigIncrements('id')->comment('主键');
            $table->bigInteger('article_id', false, true)->comment('文章ID');
            $table->bigInteger('category_id', false, true)->comment('文章分类ID');
            $table->unique(['article_id', 'category_id'], 'uk_article_category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_category_relation');
    }
}
