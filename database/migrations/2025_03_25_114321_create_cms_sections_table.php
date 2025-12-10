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
        Schema::create('cms_sections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 191);
            $table->json('content')->nullable();
            $table->string('parent_type', 191)->nullable();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->unsignedBigInteger('section_id')->nullable()->index('cms_sections_section_id_foreign');
            $table->string('type', 191);
            $table->unsignedSmallInteger('order');
            $table->boolean('disabled')->default(false);
            $table->string('button_type', 191)->nullable();
            $table->json('button_text')->nullable();
            $table->text('button_data')->nullable();
            $table->timestamps();

            $table->index(['parent_type', 'parent_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cms_sections');
    }
};
