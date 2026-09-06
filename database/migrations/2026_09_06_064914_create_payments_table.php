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
        Schema::create('payment', function (Blueprint $table) {
            $table->id();

            $table->foreignId('client_id')
                ->constrained('client')
                ->cascadeOnDelete();

            $table->date('payment_date');

            $table->decimal('total_paid', 12, 2)->default(0);

            $table->string('payment_method')->nullable();
            // Cash, Bank Transfer, Cheque, Card, etc.

            $table->string('reference_number')->nullable();

            $table->text('notes')->nullable();

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });

        Schema::create('payment_invoice', function (Blueprint $table) {
            $table->id();

            $table->foreignId('payment_id')
                ->constrained('payment')
                ->cascadeOnDelete();

            $table->foreignId('invoice_id')
                ->constrained('invoice')
                ->restrictOnDelete();

            $table->decimal('amount', 12, 2);

            $table->timestamps();

            $table->unique(['payment_id', 'invoice_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_invoice');
        Schema::dropIfExists('payment');
    }
};
