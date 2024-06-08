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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->string('photo')->nullable();
            $table->boolean('active')->default(true);
            $table->foreignId('driver_id')->constrained('drivers');
            $table->string('name');
            $table->string('brand');
            $table->string('model');
            $table->string('plate');
            $table->string('color');
            $table->string('year');
            $table->string('pax');
            $table->boolean('armoured_car')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
