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
        Schema::create('members_program', function (Blueprint $table) {
            $table->id();
            $table->string("app_no")->nullable();
            $table->integer("encoder_id")->nullable();
            $table->integer("agent_id")->nullable();
            $table->integer("member_id")->nullable();
            $table->integer("program_id")->nullable();
            $table->integer("branch_id")->nullable();
            $table->integer("claimant_id")->nullable();
            $table->string("beneficiaries_ids")->nullable();
            $table->string("or_number")->nullable();
            $table->dateTime("or_date")->nullable();
            $table->integer("registration_fee")->nullable();
            $table->integer("amount")->nullable();
            $table->integer("incentives")->nullable();
            $table->integer("incentives_total")->nullable();
            $table->integer("fidelity")->nullable();
            $table->integer("fidelity_total")->nullable();
            $table->integer("net")->nullable();
            $table->string("transaction_type")->nullable();
            $table->string("contact_person")->nullable();
            $table->string("contact_person_num")->nullable();
            $table->string("status")->nullable();
            $table->boolean("is_deleted")->nullable()->default(false);
            $table->boolean("is_remitted")->nullable()->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members_program');
    }
};
