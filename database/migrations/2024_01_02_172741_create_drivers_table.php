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
        Schema::create('drivers', function (Blueprint $table) {
            $table->id();
            $table->string('photo')->nullable();
            $table->string('name');
            $table->string('phone_number')->unique()->required();
            $table->string('email')->unique();
            $table->string('cpf')->unique();
            $table->date('birth_date');
            $table->string('gender')->nullable();
            $table->string('cep');
            $table->string('address');
            $table->integer('number');
            $table->string('complement');
            $table->string('neighborhood');
            $table->string('city');
            $table->string('state');
            $table->string('obs')->nullable();
            $table->boolean('status')->required();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('drivers');
    }
};
