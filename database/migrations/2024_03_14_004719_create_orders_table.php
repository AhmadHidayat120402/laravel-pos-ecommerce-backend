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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('address_id')->constrained('addresses')->onDelete('cascade')->default(0);
            $table->integer('id_kasir')->nullable();
            $table->string('nama_kasir')->nullable();
            $table->integer('subtotal');
            $table->integer('shipping_cost')->nullable();
            $table->integer('total_cost');
            $table->integer('tax')->nullable();
            $table->integer('service_charge')->nullable();
            $table->integer('discount')->nullable();
            $table->integer('total_item')->nullable();
            $table->enum('status', ['pending', 'paid', 'on_delivery', 'delivered', 'expired', 'cancelled']);
            $table->enum('payment_method', ['bank_transfer', 'ewallet', 'cash', 'qris']);
            $table->string('payment_va_name')->nullable();
            $table->string('payment_va_number')->nullable();
            $table->string('payment_ewallet')->nullable();
            $table->integer('payment_amount')->nullable();
            $table->string('shipping_service')->nullable();
            $table->string('shipping_resi')->nullable();
            $table->string('transaction_number')->nullable();
            $table->string('transaction_time')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
