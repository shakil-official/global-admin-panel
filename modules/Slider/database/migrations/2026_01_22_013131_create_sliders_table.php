<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();

            // Basic info
            $table->string('name'); // internal name or title of slide
            $table->integer('position'); // slide order
            $table->string('image'); // background image path
            $table->enum('type', ['mobile', 'web'])->default('web'); // web/mobile
            $table->enum('status', ['active', 'inactive'])->default('active');

            // Content
            $table->string('badge')->nullable(); // "Sylhet's Most Trusted ISP Since 2014"
            $table->string('headline')->nullable(); // main <h1>
            $table->text('description')->nullable(); // <p> hero description

            // Button(s)
            $table->string('button_text')->nullable(); // "View Packages"
            $table->string('button_url')->nullable();  // "/packages"
            $table->string('button_icon')->nullable(); // optional icon class
            $table->string('button_classes')->nullable(); // btn classes

            // Overlay / extra effects
            $table->boolean('overlay_enabled')->default(true); // if overlay div needed

            // Feature pills (JSON)
            $table->json('feature_pills')->nullable();
            // Example: [{"icon":"check-circle","label":"Local CDN"}, {"icon":"check-circle","label":"HD Facebook"}]

            // User info
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sliders');
    }
};
