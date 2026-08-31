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
        Schema::create('invoice', function (Blueprint $table) {
            $table->id();

            $table->date('invoice_date');

            $table->string('invoice_number')->unique();
            $table->string('invoice_number_prefix', 20)->nullable();
            $table->unsignedBigInteger('invoice_number_suffix')->nullable();

            $table->date('due_date')->nullable();

            $table->enum('payment_status', [
                'Paid',
                'Partially Paid',
                'Unpaid'
            ])->default('Unpaid');

            $table->foreignId('client_id')
                ->constrained('client')
                ->restrictOnDelete();
            $table->foreignId('service_id')
                ->constrained('service')
                ->restrictOnDelete();
        
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->decimal('quantity', 10, 2)->default(1);
            $table->decimal('net_amount', 12, 2)->default(0);
            $table->decimal('vat', 12, 2)->default(0);           
            $table->decimal('discount', 12, 2)->default(0);
            $table->decimal('total', 12, 2)->default(0); 

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice');
    }
};
