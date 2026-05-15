<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('generals', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('whats_app_contact');
            $table->string('facebook_contact')->nullable();

            $table->string('fav')->nullable();
            $table->string('icon')->nullable();
            $table->string('btrc_document_file')->nullable();

            $table->text('social_facebook_link')->nullable();
            $table->text('social_youtube_link')->nullable();
            $table->text('social_linkdin_link')->nullable();
            $table->text('social_instragram_link')->nullable();

            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->foreignId('user_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('generals');
    }
};
