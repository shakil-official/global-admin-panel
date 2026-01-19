<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [

        /* Property Identification */
        'property_code',
        'status',
        'property_type',
        'property_sub_type',

        /* Location & Address */
        'address',
        'city',
        'state',
        'zipcode',
        'country',
        'latitude',
        'longitude',

        /* Pricing & Financials */
        'price',
        'price_per_sqft',
        'hoa_fee',
        'property_tax',

        /* Property Details */
        'bedrooms',
        'bathrooms',
        'area_sqft',
        'lot_size',
        'year_built',
        'floors',
        'parking_spaces',

        /* Features & Amenities */
        'has_pool',
        'has_garage',
        'has_garden',
        'has_balcony',
        'has_elevator',
        'is_furnished',

        /* Media & Presentation */
        'main_image',
        'gallery',
        'video_url',
        'virtual_tour_url',

        'global_sub_category_id'
    ];

    protected $casts = [
        'gallery' => 'array',
        'has_pool' => 'boolean',
        'has_garage' => 'boolean',
        'has_garden' => 'boolean',
        'has_balcony' => 'boolean',
        'has_elevator' => 'boolean',
        'is_furnished' => 'boolean',
    ];


    // Category
    public function subCategory()
    {
        return $this->belongsTo(GlobalSubCategory::class, 'global_sub_category_id');
    }

}
