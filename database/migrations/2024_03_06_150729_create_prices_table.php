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
        Schema::create('prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hall_id')->constrained('halls');
            $table->foreignId('district_id')->constrained('districts');
            //$table->string('price1');
            $table->decimal('price1', $precision = 10, $scale = 2);
            //$table->string('price2');
            $table->decimal('price2', $precision = 10, $scale = 2);
            //$table->string('price3');
            $table->decimal('price3', $precision = 10, $scale = 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prices');
    }
};
