<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('seos', function (Blueprint $table) {
            $table->id();

            // Core SEO
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('image');

            // Content
            $table->tinyText('short_description');
            $table->text('description');

            // Basic SEO fields
            $table->string('keywords')->nullable();
            $table->string('canonical')->nullable();

            // Advanced SEO (ALL in ONE JSON - scalable)
            $table->json('seo_settings')->nullable();

            // Relations
            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Type & Status
            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seos');
    }
};
