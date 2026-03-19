<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->string('slug')->unique();
            $table->string('image')->nullable();

            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');

            // CATEGORY
            $table->string('category');

            // PACKAGE DATA
            $table->string('speed')->nullable(); // support "Custom", "Coming Soon"
            $table->decimal('base_price', 10, 2)->default(0);

            // VAT
            $table->boolean('vat_enabled')->default(true);
            $table->decimal('vat_percentage', 5, 2)->default(5);
            $table->decimal('vat_amount', 10, 2)->nullable();
            $table->decimal('total_price', 10, 2)->nullable();

            // FLAGS
            $table->boolean('is_popular')->default(false);

            // FEATURES (repeatable)
            $table->json('features')->nullable();

            $table->tinyText('short_description')->nullable();
            $table->text('description')->nullable();

            $table->enum('type', ['upstream'])->default('upstream');
            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};
