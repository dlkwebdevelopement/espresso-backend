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
        Schema::create('item_attribute_options', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('item_attribute_id');
            $table->foreign('item_attribute_id')->references('id')->on('item_attributes')->onDelete('cascade');
            $table->string('value'); // e.g. Large, Less Sugar
            $table->decimal('price_adjustment', 10, 3)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('item_attribute_options');
    }
};
