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
        Schema::create('beneficiaries', function (Blueprint $table) {
            $table->id();
            $table->string("fname")->nullable();
            $table->string("mname")->nullable();
            $table->string("lname")->nullable();
            $table->string("ext")->nullable();
            $table->timestamp("birthdate")->nullable();
            $table->string("sex")->nullable();
            $table->string("relationship")->nullable();
            $table->string("contact_num")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beneficiaries');
    }
};
