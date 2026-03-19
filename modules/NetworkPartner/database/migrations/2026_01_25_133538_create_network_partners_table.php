<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('network_partners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('image');
            $table->unsignedBigInteger('user_id')->nullable(); // Make nullable for onDelete('set null')
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->enum('type', ['upstream', 'peering', 'technology', 'nttn']);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('network_partners');
    }
};
