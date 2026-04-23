<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hero_slides', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title_en');
            $table->string('title_ar')->nullable();
            $table->string('subtitle_en')->nullable();
            $table->string('subtitle_ar')->nullable();
            $table->string('badge_text')->nullable();           // e.g. "✨ New Arrivals"
            $table->string('button_text')->default('Shop Now');
            $table->string('button_link')->default('/products');
            $table->string('bg_color_from')->default('#BAE6FD'); // gradient start
            $table->string('bg_color_to')->default('#FFB5C5');   // gradient end
            $table->string('text_color')->default('#0F172A');    // main text color
            $table->string('image_1')->nullable();               // left floating image
            $table->string('image_2')->nullable();               // center/main image
            $table->string('image_3')->nullable();               // right floating image
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hero_slides');
    }
};
