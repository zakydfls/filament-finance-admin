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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string("customer_name");
            $table->string("customer_phone")->nullable();
            $table->string("customer_car")->nullable();
            $table->string("customer_car_number")->nullable();
            $table->foreignId("category_id")->constrained('categories')->cascadeOnDelete();
            $table->date("date");
            $table->string("note")->nullable();
            $table->string("image")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
