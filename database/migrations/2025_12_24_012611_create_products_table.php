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
        Schema::create('products', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->string('name');
            $table->string('description');
            $table->string('condition');
            $table->char('merchant_id', 36);
            $table->char('product_category_id', 36);
            $table->foreign('merchant_id')->references('id')->on('merchants');
            $table->foreign('product_category_id')->references('id')->on('product_categories');
            $table->timestamps();
        });

        Schema::table('products', function (Blueprint $table) {
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
        Schema::table('products', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::dropIfExists('products');
    }
};
