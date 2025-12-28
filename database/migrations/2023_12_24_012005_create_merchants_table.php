<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchants', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->char('user_id', 36);
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('name');
            $table->string('phone');
            $table->string('image')->nullable();
            $table->string('catch_phrase')->nullable();
            $table->string('process_time')->nullable();
            $table->string('operational_time')->nullable();
            $table->string('banner_image')->nullable();
            $table->string('description')->nullable();
            $table->string('full_description')->nullable();
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
        Schema::dropIfExists('merchants');
    }
};
