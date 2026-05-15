<?php

namespace Modules\Seo\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Modules\Seo\Models\Seo;
use Modules\Seo\Services\Contracts\SeoServiceInterface;
use Symfony\Component\HttpFoundation\Response;

class SeoApiController extends Controller
{
    protected SeoServiceInterface $service;

    public function __construct(SeoServiceInterface $service)
    {
        $this->service = $service;
    }


    public function settings(): JsonResponse
    {
        $seo = Seo::query()
            ->where('status', 'active')
            ->latest()
            ->first();



        if (!$seo) {
            return response()->json([
                'success' => false,
                'message' => 'SEO settings not found',
                'data' => []
            ], 404);
        }

        $seoSettings = is_array($seo->seo_settings)
            ? $seo->seo_settings
            : [];


        return response()->json([
            'success' => true,
            'data' => [

                /*
                |--------------------------------------------------------------------------
                | Core SEO
                |--------------------------------------------------------------------------
                */
                'title' => $seo->title ?? null,
                'description' => $seo->description ?? null,
                'shortDescription' => $seo->short_description ?? null,
                'keywords' => $seo->keywords ?? null,
                'canonical' => $seo->canonical ?? null,

                /*
                |--------------------------------------------------------------------------
                | Image
                |--------------------------------------------------------------------------
                */
                'image' => !empty($seo->image)
                    ? asset($seo->image)
                    : null,

                /*
                |--------------------------------------------------------------------------
                | Meta SEO
                |--------------------------------------------------------------------------
                */
                'meta_title' => $seoSettings['meta_title'] ?? null,
                'meta_description' => $seoSettings['meta_description'] ?? null,

                /*
                |--------------------------------------------------------------------------
                | Twitter
                |--------------------------------------------------------------------------
                */
                'twitterHandle' => $seoSettings['twitterHandle'] ?? null,
                'twitterCreator' => $seoSettings['twitterCreator'] ?? null,

                'twitter_handle' => $seoSettings['twitter_handle'] ?? null,
                'twitter_creator' => $seoSettings['twitter_creator'] ?? null,

                /*
                |--------------------------------------------------------------------------
                | Robots
                |--------------------------------------------------------------------------
                */
                'robotsIndex' => $seoSettings['robotsIndex'] ?? true,
                'robotsFollow' => $seoSettings['robotsFollow'] ?? true,

                /*
                |--------------------------------------------------------------------------
                | Verification
                |--------------------------------------------------------------------------
                */
                'googleVerification' => $seoSettings['googleVerification'] ?? null,
                'bingVerification' => $seoSettings['bingVerification'] ?? null,
                'yandexVerification' => $seoSettings['yandexVerification'] ?? null,

                'google_verification' => $seoSettings['google_verification'] ?? null,
                'bing_verification' => $seoSettings['bing_verification'] ?? null,
                'yandex_verification' => $seoSettings['yandex_verification'] ?? null,

                /*
                |--------------------------------------------------------------------------
                | Geo
                |--------------------------------------------------------------------------
                */
                'geoRegion' => $seoSettings['geoRegion'] ?? null,
                'geoPlacename' => $seoSettings['geoPlacename'] ?? null,
                'geoPosition' => $seoSettings['geoPosition'] ?? null,

                'geo_region' => $seoSettings['geo_region'] ?? null,
                'geo_placename' => $seoSettings['geo_placename'] ?? null,
                'geo_position' => $seoSettings['geo_position'] ?? null,

                /*
                |--------------------------------------------------------------------------
                | Organization
                |--------------------------------------------------------------------------
                */
                'organizationName' => $seoSettings['organizationName'] ?? null,
                'organizationLogo' => $seoSettings['organizationLogo'] ?? null,

                'organization_name' => $seoSettings['organization_name'] ?? null,
                'organization_logo' => $seoSettings['organization_logo'] ?? null,

                /*
                |--------------------------------------------------------------------------
                | Contact
                |--------------------------------------------------------------------------
                */
                'phoneNumbers' => $seoSettings['phoneNumbers'] ?? [],
                'emails' => $seoSettings['emails'] ?? [],

                /*
                |--------------------------------------------------------------------------
                | Social
                |--------------------------------------------------------------------------
                */
                'facebookUrl' => $seoSettings['facebookUrl'] ?? null,
                'youtubeUrl' => $seoSettings['youtubeUrl'] ?? null,

                'facebook_url' => $seoSettings['facebook_url'] ?? null,
                'youtube_url' => $seoSettings['youtube_url'] ?? null,

                /*
                |--------------------------------------------------------------------------
                | Tracking
                |--------------------------------------------------------------------------
                */

                'gtmId' => $seoSettings['gtmId'] ?? null,
                'fbPixelId' => $seoSettings['fbPixelId'] ?? null,
                'clarityId' => $seoSettings['clarityId'] ?? null,
            ]
        ]);
    }


}
