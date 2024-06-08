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
        Schema::create('invoicings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('transfer_id')->constrained('transfers');
            $table->string('type'); //in or out
            $table->string('status'); //paid or unpaid
            $table->date('date_invoiced');
            //$table->float('amount');
            $table->decimal('amount', $precision = 10, $scale = 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoicings');
    }
};
