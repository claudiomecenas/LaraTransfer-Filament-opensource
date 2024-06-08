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
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            //$table->float('negotiated_value');
            $table->decimal('negotiated_value', $precision = 10, $scale = 2);
            $table->string('payment_type')->default('PIX');
            $table->foreignId('client_id')->constrained('clients');
            $table->foreignId('car_id')->constrained('cars');
            $table->date('departure_date');
            $table->time('departure_time');
            $table->string('destination_address');
            $table->string('origin_address');
            $table->integer('pax');
            $table->integer('stops');
            $table->integer('bags');
            $table->longText('obs')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfers');
    }
};
