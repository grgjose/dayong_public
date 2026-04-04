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
        Schema::create('excel_members', function (Blueprint $table) {
            $table->id();
            $table->string("timestamp")->nullable();
            $table->string("branch")->nullable();
            $table->string("marketting_agent")->nullable();
            $table->string("status")->nullable();
            $table->string("phmember")->nullable();
            $table->string("address")->nullable();
            $table->string("civil_status")->nullable();
            $table->string("birthdate")->nullable();
            $table->string("age")->nullable();
            $table->string("name")->nullable();
            $table->string("contact_num")->nullable();
            $table->string("type_of_transaction")->nullable();
            $table->string("with_registration_fee")->nullable();
            $table->string("registration_amount")->nullable();
            $table->string("dayong_program")->nullable();
            $table->string("application_no")->nullable();
            $table->string("or_number")->nullable();
            $table->string("or_date")->nullable();
            $table->string("amount_collected")->nullable();

            $table->string("name1")->nullable();
            $table->string("age1")->nullable();
            $table->string("relationship1")->nullable();
            $table->string("name2")->nullable();
            $table->string("age2")->nullable();
            $table->string("relationship2")->nullable();
            $table->string("name3")->nullable();
            $table->string("age3")->nullable();
            $table->string("relationship3")->nullable();
            $table->string("name4")->nullable();
            $table->string("age4")->nullable();
            $table->string("relationship4")->nullable();
            $table->string("name5")->nullable();
            $table->string("age5")->nullable();
            $table->string("relationship5")->nullable();
            
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
        Schema::dropIfExists('excel_members');
    }
};
