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
        Schema::create('company_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('tagline_id');
            $table->string('tagline_en');
            $table->text('about_id');
            $table->text('about_en');
            $table->text('vision_id');
            $table->text('vision_en');
            $table->text('mission_id');
            $table->text('mission_en');
            $table->text('address');
            $table->string('phone', 50);
            $table->string('whatsapp', 50);
            $table->string('email');
            $table->text('maps_embed_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_profiles');
    }
};
