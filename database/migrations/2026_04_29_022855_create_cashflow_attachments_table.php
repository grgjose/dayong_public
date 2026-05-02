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
        Schema::create('cashflow_attachments', function (Blueprint $table) {
            $table->id();
 
            // Polymorphic columns:
            // attachable_type = "App\Models\Remittance" or "App\Models\Expense"
            // attachable_id   = the ID of the parent record
            $table->string('attachable_type');
            $table->integer('attachable_id');
 
            $table->string('file_path');        // storage path, e.g. cashflow_attachments/xyz.jpg
            $table->string('original_name');    // original filename shown to the user
            $table->integer('uploaded_by');     // user ID of the uploader
 
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cashflow_attachments');
    }
};
