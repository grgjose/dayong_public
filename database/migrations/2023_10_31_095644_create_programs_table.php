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
        Schema::create('programs', function (Blueprint $table) {
            $table->id();
            $table->string("code")->nullable();
            $table->string("description")->nullable();
            $table->integer("beneficiaries_count")->default(2)->nullable();
            // Member Age Limits
            $table->integer("age_min")->nullable();
            $table->integer("age_max")->nullable();
            // Beneficiary Age Limits
            $table->integer("ben_age_min")->nullable();
            $table->integer("ben_age_max")->nullable();
            // Term Limits
            $table->integer("term_min")->nullable();
            $table->integer("term_max")->nullable();
            $table->decimal("amount_min", 15, 2)->nullable();
            $table->decimal("amount_max", 15, 2)->nullable();
            $table->string("status")->nullable(); // Active, Inactive
            $table->string('is_deleted')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
