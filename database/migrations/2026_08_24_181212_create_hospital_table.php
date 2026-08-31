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
        Schema::create('hospital', function (Blueprint $table) {
            $table->id();

            $table->string('hospital_name');
            $table->string('trade_license_number')->nullable();
            $table->string('ownership_type')->nullable();
            $table->string('tax_registration_number')->nullable();

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

            $table->string('telephone_number')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();

            $table->string('logo')->nullable();
            $table->string('company_seal')->nullable();

            $table->string('invoice_number_prefix', 20)->default('INV');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hospital');
    }
};
