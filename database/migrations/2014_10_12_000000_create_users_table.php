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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->nullable();
            $table->integer('usertype')->nullable();
            $table->string('fname')->nullable();
            $table->string('mname')->nullable();
            $table->string('lname')->nullable();
            $table->string('ext')->nullable();
            $table->string('email')->unique()->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('contact_num')->nullable();
            $table->timestamp('birthdate')->nullable();
            $table->string('address')->nullable();
            $table->integer('branch_id')->nullable();
            $table->string('profile_pic')->nullable();
            $table->boolean('with_fidelity')->nullable();
            $table->string('password')->nullable();
            $table->string('status')->nullable(); // Pending Registration, Active, Inactive, Suspended, Resigned
            $table->string('reset_token')->nullable();
            $table->timestamp('reset_token_sent')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
