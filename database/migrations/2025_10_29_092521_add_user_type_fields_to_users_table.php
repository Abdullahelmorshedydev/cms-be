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
        Schema::table('users', function (Blueprint $table) {
            // Common fields for all users
            $table->string('phone')->nullable()->after('email_verified_at');
            $table->json('address')->nullable()->after('phone');
            $table->text('bio')->nullable()->after('address');
            $table->string('job_title')->nullable()->after('bio');
            $table->boolean('is_admin')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'phone',
                'address',
                'bio',
                'job_title',
            ]);
        });
    }
};
