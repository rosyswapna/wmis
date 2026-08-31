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
        Schema::create('client', function (Blueprint $table) {
            $table->id();

            $table->string('name');

            $table->text('address')->nullable();

            $table->foreignId('country_id')
                ->nullable()
                ->constrained('country')
                ->nullOnDelete();

            $table->foreignId('state_id')
                ->nullable()
                ->constrained('state')
                ->nullOnDelete();

            $table->foreignId('city_id')
                ->nullable()
                ->constrained('city')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client');
    }
};
