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
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // Form type (contact, newsletter, support, etc.)
            $table->string('name')->nullable(); // Sender name
            $table->string('email')->nullable(); // Sender email
            $table->string('phone')->nullable(); // Sender phone
            $table->string('subject')->nullable(); // Form subject
            $table->text('message')->nullable(); // Form message
            $table->json('data')->nullable(); // Additional form data (flexible JSON)
            $table->string('ip_address', 45)->nullable(); // IPv4 or IPv6
            $table->string('user_agent')->nullable(); // Browser/device info
            $table->boolean('is_read')->default(false); // Read status
            $table->timestamp('read_at')->nullable(); // When marked as read
            $table->tinyInteger('is_active')->default(1); // Status (active/inactive)
            $table->timestamps();

            // Indexes for better query performance
            $table->index('type');
            $table->index('email');
            $table->index('is_read');
            $table->index('is_active');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forms');
    }
};
