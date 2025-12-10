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
        Schema::table('cms_sections', function (Blueprint $table) {
            $table->foreign(['section_id'])->references(['id'])->on('cms_sections')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cms_sections', function (Blueprint $table) {
            $table->dropForeign('cms_sections_section_id_foreign');
        });
    }
};
