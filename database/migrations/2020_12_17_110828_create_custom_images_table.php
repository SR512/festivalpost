<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('custom_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('custom_category_id')->nullable();
            $table->string('image_one')->nullable();
            $table->double('position_x')->nullable();
            $table->double('position_y')->nullable();
            $table->double('imgposition_x')->nullable();
            $table->double('imgposition_y')->nullable();
            $table->double('width')->nullable();
            $table->double('height')->nullable();
            $table->integer('status')->default(0);
            $table->timestamps();

            $table->foreign('custom_category_id')->references('id')->on('custom_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('custom_images');
    }
}
