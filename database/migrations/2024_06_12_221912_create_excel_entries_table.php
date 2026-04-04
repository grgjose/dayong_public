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
        Schema::create('excel_entries', function (Blueprint $table) {
            $table->id();
            $table->string("timestamp")->nullable();
            $table->string("branch")->nullable();
            $table->string("marketting_agent")->nullable();
            $table->string("status")->nullable();
            $table->string("phmember")->nullable();
            $table->string("or_number")->nullable();
            $table->string("or_date")->nullable();
            $table->string("amount_collected")->nullable();
            $table->string("month_of")->nullable();
            $table->string("nop")->nullable();
            $table->string("date_remitted")->nullable();
            $table->string("dayong_program")->nullable();
            $table->string("reactivation")->nullable();
            $table->string("transferred")->nullable();

            $table->string("sheetName")->nullable();
            $table->string("remarks")->nullable();
            $table->boolean("isImported")->default(false)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('excel_entries');
    }
};
