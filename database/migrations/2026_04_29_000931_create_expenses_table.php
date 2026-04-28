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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->integer('branch_id');
            $table->integer('encoder_id');
            $table->integer('mas_id')->nullable();
            $table->integer('member_id')->nullable();
            $table->string('type_of_expense');
            $table->string('receipt_number')->nullable();
            $table->double('amount');
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
        Schema::dropIfExists('expenses');
    }
};
