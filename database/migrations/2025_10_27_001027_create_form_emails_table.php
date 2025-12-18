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
        Schema::create('form_emails', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Recipient name/identifier
            $table->string('email')->unique(); // Email address
            $table->json('form_types'); // Array of form types this email receives
            $table->tinyInteger('is_active')->default(1); // Status (active/inactive)
            $table->timestamps();

            // Indexes for better query performance
            $table->index('email');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_emails');
    }
};
