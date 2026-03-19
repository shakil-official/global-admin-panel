<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug');
            $table->string('image');
            $table->unsignedBigInteger('category_id')->nullable(); // Make nullable for onDelete('set null')
            $table->foreign('category_id')->references('id')->on('categories')->onDelete('set null');
            $table->unsignedBigInteger('user_id')->nullable(); // Make nullable for onDelete('set null')
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->tinyText('short_description');
            $table->longText('description');
            $table->enum('type', ['coverage', 'blog', 'internet']);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
