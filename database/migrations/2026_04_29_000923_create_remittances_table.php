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
        Schema::create('remittances', function (Blueprint $table) {
            $table->id();
            $table->integer('branch_id');
            $table->integer('encoder_id');
            $table->integer('mas_id')->nullable();
            $table->string('mas_name')->nullable();
            $table->enum('transaction_type', ['bank', 'gcash']);
            $table->double('amount');
            $table->string('bank_name')->nullable();
            $table->string('gcash_number')->nullable();
            $table->string('reference_number')->nullable();
            $table->date('transaction_date');
            $table->string('remarks')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('remittances');
    }
};
