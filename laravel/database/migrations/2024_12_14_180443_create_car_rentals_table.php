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
        Schema::create('car_rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->constrained('cars');
            $table->foreignId('client_id')->constrained('clients');
            $table->timestamp('borrowed_at');
            $table->timestamp('returned_at')->nullable();
            $table->decimal('borrowed_latitude', 10, 7);
            $table->decimal('borrowed_longitude', 10, 7);
            $table->decimal('returned_latitude', 10, 7)->nullable();
            $table->decimal('returned_longitude', 10, 7)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_rentals');
    }
};
