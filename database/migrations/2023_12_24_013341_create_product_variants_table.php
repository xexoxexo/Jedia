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
        Schema::create('product_variants', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->char('product_id', 36);
            $table->foreign('product_id')->references('id')->on('products');
            $table->string('name');
            $table->integer('stock');
            $table->integer('price');
            $table->timestamps();
        });

        Schema::table('product_variants', function (Blueprint $table) {
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
        Schema::table('product_variants', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });

        Schema::dropIfExists('product_variants');
    }
};
