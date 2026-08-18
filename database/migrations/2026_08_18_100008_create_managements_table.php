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
        Schema::create('managements', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('position_id');
            $table->string('position_en');
            $table->text('bio_id')->nullable();
            $table->text('bio_en')->nullable();
            $table->string('photo_path')->nullable();
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
        Schema::dropIfExists('managements');
    }
};
