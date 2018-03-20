<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('images', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('gallery_id')->unsigned();
            $table->string('image')->nullable();
            $table->string('public_id')->nullable();
            $table->string('format')->nullable();
            $table->string('description')->nullable();
            $table->boolean('published');
            $table->integer('order')->unsigned();
            $table->integer('width')->unsigned();
            $table->integer('height')->unsigned();
            $table->integer('bytes')->unsigned();
            $table->foreign('gallery_id')->references('id')->on('galleries')->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('images');
    }
}
