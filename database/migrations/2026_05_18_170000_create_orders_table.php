<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->json('items');
            $table->decimal('subtotal', 8, 3);
            $table->decimal('service_charge', 8, 3);
            $table->decimal('vat', 8, 3);
            $table->decimal('tip', 8, 3)->default(0);
            $table->decimal('total', 8, 3);
            $table->string('status')->default('ordered');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
