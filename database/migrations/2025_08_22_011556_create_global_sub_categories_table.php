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
        Schema::create('global_sub_categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('global_category_id'); // relation to global_categories
            $table->string('name');
            $table->string('slug')->nullable();
            $table->integer('position')->default(1);
            $table->integer('reason')->default(1);
            $table->longText('description')->nullable();
            $table->string('image')->nullable();
            $table->string('icon')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();

            // Add foreign key constraint
            $table->foreign('global_category_id')
                ->references('id')->on('global_categories')
                ->onDelete('cascade'); // when category is deleted, subcategories are deleted too
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('global_sub_categories');
    }
};
