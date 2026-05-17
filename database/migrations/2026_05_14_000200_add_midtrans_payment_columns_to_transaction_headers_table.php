<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transaction_headers', function (Blueprint $table) {
            $table->string('payment_gateway')->nullable()->after('location_id');
            $table->string('payment_order_id')->nullable()->unique()->after('payment_gateway');
            $table->integer('payment_gross_amount')->nullable()->after('payment_order_id');
            $table->string('payment_type')->nullable()->after('payment_gross_amount');
            $table->string('payment_method')->nullable()->after('payment_type');
            $table->string('payment_status')->nullable()->after('payment_method');
            $table->text('payment_redirect_url')->nullable()->after('payment_status');
            $table->timestamp('paid_at')->nullable()->after('payment_redirect_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaction_headers', function (Blueprint $table) {
            $table->dropUnique(['payment_order_id']);
            $table->dropColumn([
                'payment_gateway',
                'payment_order_id',
                'payment_gross_amount',
                'payment_type',
                'payment_method',
                'payment_status',
                'payment_redirect_url',
                'paid_at',
            ]);
        });
    }
};
