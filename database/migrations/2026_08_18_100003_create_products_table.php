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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->restrictOnDelete();
            $table->foreignId('brand_id')->constrained('brands')->restrictOnDelete();
            $table->string('name_id');
            $table->string('name_en');
            $table->string('slug_id')->unique();
            $table->string('slug_en')->unique();
            $table->text('summary_id')->nullable();
            $table->text('summary_en')->nullable();
            $table->longText('description_id')->nullable();
            $table->longText('description_en')->nullable();
            $table->json('specifications')->nullable();
            $table->string('primary_image_path');
            $table->string('brochure_path')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
