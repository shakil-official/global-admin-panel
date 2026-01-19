<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();

            /* Property Identification */
            $table->string('property_code')->unique();
            $table->enum('status', ['sale', 'rent', 'sold', 'pending']);
            $table->string('property_type');
            $table->string('property_sub_type')->nullable();

            /* Location & Address */
            $table->string('address');
            $table->string('city');
            $table->string('state')->nullable();
            $table->string('zipcode')->nullable();
            $table->string('country')->default('USA');
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();

            /* Pricing & Financials */
            $table->decimal('price', 15, 2);
            $table->decimal('price_per_sqft', 10, 2)->nullable();
            $table->decimal('hoa_fee', 10, 2)->nullable();
            $table->decimal('property_tax', 10, 2)->nullable();

            /* Property Details */
            $table->integer('bedrooms')->default(0);
            $table->decimal('bathrooms', 4, 1)->default(0);
            $table->integer('area_sqft');
            $table->integer('lot_size')->nullable();
            $table->year('year_built')->nullable();
            $table->integer('floors')->nullable();
            $table->integer('parking_spaces')->nullable();

            /* Features & Amenities */
            $table->boolean('has_pool')->default(false);
            $table->boolean('has_garage')->default(false);
            $table->boolean('has_garden')->default(false);
            $table->boolean('has_balcony')->default(false);
            $table->boolean('has_elevator')->default(false);
            $table->boolean('is_furnished')->default(false);

            /* Media & Presentation */
            $table->string('main_image')->nullable();
            $table->json('gallery')->nullable();
            $table->string('video_url')->nullable();
            $table->string('virtual_tour_url')->nullable();

            $table->unsignedInteger('global_sub_category_id');
            $table->foreign('global_sub_category_id')
                ->references('id')->on('global_sub_categories')
                ->onDelete('cascade');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
