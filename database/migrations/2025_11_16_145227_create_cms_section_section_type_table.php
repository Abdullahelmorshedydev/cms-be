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
        Schema::create('cms_section_section_type', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cms_section_id');
            $table->unsignedBigInteger('section_type_id');
            $table->timestamps();

            $table->foreign('cms_section_id')
                ->references('id')
                ->on('cms_sections')
                ->onDelete('cascade');

            $table->foreign('section_type_id')
                ->references('id')
                ->on('section_types')
                ->onDelete('cascade');

            $table->unique(['cms_section_id', 'section_type_id'], 'cms_section_section_type_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cms_section_section_type');
    }
};
