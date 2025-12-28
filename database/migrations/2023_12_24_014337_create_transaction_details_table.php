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
        Schema::create('transaction_details', function (Blueprint $table) {
            $table->char('transaction_id', 36);
            $table->foreign('transaction_id')->references('id')->on('transaction_headers');
            $table->char('product_id', 36);
            $table->foreign('product_id')->references('id')->on('products');
            $table->char('variant_id', 36);
            $table->foreign('variant_id')->references('id')->on('product_variants');
            $table->integer('quantity');
            $table->integer('price');
            $table->char('shipment_id', 36);
            $table->foreign('shipment_id')->references('id')->on('shipments');
            $table->string('status');
            $table->string('promo_name')->nullable();
            $table->integer('discount');
            $table->integer('total_paid');
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
        Schema::dropIfExists('transaction_details');
    }
};
