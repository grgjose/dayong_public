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
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string("code")->nullable();
            $table->string("city")->nullable();
            $table->string("branch")->nullable();
            $table->string("address")->nullable();
            $table->string("description")->nullable();
            $table->string("status")->default('active')->nullable();
            $table->boolean("is_deleted")->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
