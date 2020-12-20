<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('businesses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('business_name')->nullable();
            $table->string('business_email')->nullable();
            $table->string('business_number')->nullable();
            $table->string('business_website')->nullable();
            $table->text('business_address')->nullable();
            $table->timestamp('date_premium')->nullable();
            $table->integer('isPremium')->default(0);
            $table->integer('status')->default(0);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('app_users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('businesses');
    }
}
