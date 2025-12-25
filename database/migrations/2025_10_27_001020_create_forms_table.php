<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            // Core
            $table->string('type');
            $table->string('name')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();

            // Contact
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->string('position')->nullable();
            $table->string('website')->nullable();

            // Address
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('zip_code')->nullable();

            // Message
            $table->string('subject')->nullable();
            $table->text('message')->nullable();
            $table->text('additional_notes')->nullable();

            // Preferences
            $table->date('preferred_date')->nullable();
            $table->time('preferred_time')->nullable();

            // Service / Project
            $table->string('service_type')->nullable();
            $table->string('referral_source')->nullable();
            $table->integer('duration')->nullable();
            $table->integer('participants')->nullable();
            $table->decimal('budget', 12, 2)->nullable();
            $table->string('currency', 10)->nullable();
            $table->date('project_deadline')->nullable();

            // Career
            $table->string('resume_url')->nullable();
            $table->string('portfolio_url')->nullable();
            $table->string('linkedin_url')->nullable();
            $table->integer('years_experience')->nullable();

            // Meta
            $table->json('data')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();

            // Status
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();

            $table->timestamps();
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
