<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->integer('position');
            $table->string('icon', 20)->default('avatar');
            $table->unsignedBigInteger('user_id')->nullable(); // Make nullable for onDelete('set null')
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->text('description')->nullable();
            $table->text('service')->nullable();
            $table->string('section', 30)->default('premium_section');
            $table->string('button_text')->nullable();
            $table->string('button_url')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
