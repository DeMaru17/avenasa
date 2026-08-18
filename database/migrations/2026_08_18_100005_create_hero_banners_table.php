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
        Schema::create('hero_banners', function (Blueprint $table) {
            $table->id();
            $table->string('title_id');
            $table->string('title_en');
            $table->text('subtitle_id')->nullable();
            $table->text('subtitle_en')->nullable();
            $table->string('image_path');
            $table->string('mobile_image_path')->nullable();
            $table->string('button_text_id')->nullable();
            $table->string('button_text_en')->nullable();
            $table->string('button_url')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hero_banners');
    }
};
