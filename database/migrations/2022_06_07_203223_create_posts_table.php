<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id_post');
            $table->string('title');
            $table->string('slug');
            $table->longText('content');
            $table->unsignedInteger('category_id');
            $table->string('status')->default('published'); // published,draft
            $table->string('featured_image')->nullable();
            $table->string('thumb')->nullable();

            $table->dateTime('published_date');
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('updated_by');

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
        Schema::dropIfExists('posts');
    }
}
