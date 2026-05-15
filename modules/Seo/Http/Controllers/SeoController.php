<?php

namespace Modules\Seo\Http\Controllers;

use App\Helpers\Builder\FormMaking;
use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Modules\Seo\Services\Contracts\SeoServiceInterface;

class SeoController extends Controller
{
    private SeoServiceInterface $service;

    public function __construct(SeoServiceInterface $service)
    {
        $this->service = $service;
    }

    /**
     * SAFE BUTTON HELPER
     */
    private function buttons(array $buttons = []): array
    {
        return $buttons;
    }

    public function index()
    {
        $buttons = $this->buttons([
            [
                'label' => 'Add New Item',
                'url' => route('seo.information'),
                'icon' => 'ri-add-line',
                'classes' => 'btn-sm btn-outline-primary',
            ],
        ]);

        return view('Seo::seo.index')->with([
            'title' => 'Seos',
            'buttons' => $buttons,
            'table' => 'seo_table',
            'columns' => ["Image", "Title", "Slug", "Status", "Action"],
        ]);
    }

    public function add()
    {
        $buttons = $this->buttons([
            [
                'label' => 'Back',
                'url' => route('seo.information'),
                'icon' => 'ri-arrow-left-line',
                'classes' => 'btn-sm btn-outline-danger',
            ],
        ]);

        $formConfig = (new FormMaking())
            ->action(route('seo.information'))
            ->enctype('multipart/form-data')
            ->method('POST')

            ->startRow()
            ->addFormFields([
                [
                    'type' => 'text',
                    'name' => 'title',
                    'label' => 'Seo Title',
                    'required' => true,
                    'col' => 'col-md-12 mb-3',
                ]
            ])
            ->endRow()

            ->startRow()
            ->addFormFields([
                [
                    'type' => 'textarea',
                    'name' => 'short_description',
                    'label' => 'Short Description',
                    'required' => true,
                    'col' => 'col-md-12 mb-3',
                ],
            ])
            ->endRow()


            ->startRow()
            ->addFormFields([
                [
                    'type' => 'textarea',
                    'name' => 'description',
                    'label' => 'Description',
                    'required' => true,
                    'col' => 'col-md-12 mb-3',
                ],
            ])
            ->endRow()


            // BASIC SEO
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'textarea',
                    'name' => 'keywords',
                    'label' => 'Meta Keywords',
                    'col' => 'col-md-6 mb-3',
                ],
                [
                    'type' => 'text',
                    'name' => 'canonical',
                    'label' => 'Canonical URL',
                    'col' => 'col-md-6 mb-3',
                ],
            ])
            ->endRow()

            // ADVANCED SEO (JSON fields)
            ->startRow()
            ->addFormFields([
                ['type' => 'text', 'name' => 'meta_title', 'label' => 'Meta Title', 'col' => 'col-md-6 mb-3'],
                ['type' => 'textarea', 'name' => 'meta_description', 'label' => 'Meta Description', 'col' => 'col-md-6 mb-3'],
            ])
            ->endRow()

            ->startRow()
            ->addFormFields([
                ['type' => 'text', 'name' => 'twitter_handle', 'label' => 'Twitter Handle', 'col' => 'col-md-6 mb-3'],
                ['type' => 'text', 'name' => 'twitter_creator', 'label' => 'Twitter Creator', 'col' => 'col-md-6 mb-3'],
            ])
            ->endRow()

            ->startRow()
            ->addFormFields([
                ['type' => 'text', 'name' => 'google_verification', 'label' => 'Google Verification', 'col' => 'col-md-4 mb-3'],
                ['type' => 'text', 'name' => 'bing_verification', 'label' => 'Bing Verification', 'col' => 'col-md-4 mb-3'],
                ['type' => 'text', 'name' => 'yandex_verification', 'label' => 'Yandex Verification', 'col' => 'col-md-4 mb-3'],
            ])
            ->endRow()

            ->startRow()
            ->addFormFields([
                ['type' => 'text', 'name' => 'geo_region', 'label' => 'Geo Region', 'col' => 'col-md-4 mb-3'],
                ['type' => 'text', 'name' => 'geo_placename', 'label' => 'Geo Place', 'col' => 'col-md-4 mb-3'],
                ['type' => 'text', 'name' => 'geo_position', 'label' => 'Geo Position', 'col' => 'col-md-4 mb-3'],
            ])
            ->endRow()

            ->startRow()
            ->addFormFields([
                ['type' => 'text', 'name' => 'organization_name', 'label' => 'Organization Name', 'col' => 'col-md-6 mb-3'],
                ['type' => 'text', 'name' => 'organization_logo', 'label' => 'Organization Logo', 'col' => 'col-md-6 mb-3'],
            ])
            ->endRow()

            ->startRow()
            ->addFormFields([
                ['type' => 'text', 'name' => 'facebook_url', 'label' => 'Facebook URL', 'col' => 'col-md-6 mb-3'],
                ['type' => 'text', 'name' => 'youtube_url', 'label' => 'YouTube URL', 'col' => 'col-md-6 mb-3'],
            ])
            ->endRow()

            ->startRow()
            ->addFormFields([
                [
                    'type' => 'file',
                    'name' => 'image',
                    'label' => 'Seo Image',
                    'required' => true,
                    'col' => 'col-md-12 mb-3',
                ]
            ])
            ->endRow()

            ->startRow()
            ->addFormFields([
                [
                    'type' => 'radio',
                    'name' => 'status',
                    'label' => 'Status',
                    'options' => [
                        ['value' => 'active', 'label' => 'Active', 'checked' => true],
                        ['value' => 'inactive', 'label' => 'Inactive', 'checked' => false],
                    ],
                    'required' => true,
                    'col' => 'col-md-6 mb-3',
                ]
            ])
            ->endRow()

            ->startRow()
            ->addInput([
                'type' => 'submit',
                'value' => 'Add SEO',
                'class' => 'btn btn-primary btn-sm',
            ])
            ->endRow()

            ->build();

        return view('Seo::seo.add')->with([
            'title' => 'Add SEO',
            'buttons' => $buttons,
            'formConfig' => $formConfig
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => 'required|unique:seos,title',
            'short_description' => 'required|max:300',
            'description' => 'required|string',
            'keywords' => 'nullable|string|max:255',
            'canonical' => 'nullable|url|max:255',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        try {
            $data = $request->only([
                'title',
                'short_description',
                'description',
                'keywords',
                'canonical',
                'status'
            ]);

            $data['slug'] = Str::slug($request->title);

            if ($request->hasFile('image')) {
                $path = public_path('uploads/seo');

                if (!File::exists($path)) {
                    File::makeDirectory($path, 0755, true);
                }

                $file = $request->file('image');
                $name = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move($path, $name);

                $data['image'] = 'uploads/seo/' . $name;
            }

            $data['user_id'] = auth()->id();

            $this->service->create($data);

            return redirect()->route('seo.information')->with('success', 'SEO created');

        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Failed');
        }
    }

    public function edit()
    {
        $id = 1;
        $seo = $this->service->find(1);

        $buttons = $this->buttons([
            [
                'label' => 'Back',
                'url' => route('seo.information'),
                'icon' => 'ri-arrow-left-line',
                'classes' => 'btn-sm btn-outline-danger',
            ],
        ]);

        $formConfig = (new FormMaking())
            ->action(route('seo.update', $id))
            ->enctype('multipart/form-data')
            ->method('POST')
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'text',
                    'name' => 'gtmId',
                    'label' => 'Seo GTM Id',
                    'required' => false,
                    'value' => $seo->seo_settings['gtmId']  ?? '',
                    'col' => 'col-md-12 mb-3',
                ]
            ])
            ->endRow()

            ->startRow()
            ->addFormFields([
                [
                    'type' => 'text',
                    'name' => 'fbPixelId',
                    'label' => 'Seo Fb Pixel Id',
                    'required' => false,
                    'value' => $seo->seo_settings['fbPixelId']  ?? '',
                    'col' => 'col-md-12 mb-3',
                ]
            ])
            ->endRow()

            ->startRow()
            ->addFormFields([
                [
                    'type' => 'text',
                    'name' => 'clarityId',
                    'label' => 'Seo Clarity Id',
                    'required' => false,
                    'value' => $seo->seo_settings['clarityId']  ?? '',
                    'col' => 'col-md-12 mb-3',
                ]
            ])
            ->endRow()


            ->startRow()
            ->addFormFields([
                [
                    'type' => 'text',
                    'name' => 'title',
                    'label' => 'Seo Title',
                    'required' => true,
                    'value' => $seo->title ?? '',
                    'col' => 'col-md-12 mb-3',
                ]
            ])
            ->endRow()

            // Short Description
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'textarea',
                    'name' => 'short_description',
                    'label' => 'Short Description',
                    'required' => true,
                    'value' => $seo->short_description ?? '',
                    'col' => 'col-md-12 mb-3',
                ],
            ])
            ->endRow()

            // Description
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'textarea',
                    'name' => 'description',
                    'label' => 'Description',
                    'required' => true,
                    'value' => $seo->description ?? '',
                    'col' => 'col-md-12 mb-3',
                ],
            ])
            ->endRow()

            // BASIC SEO
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'textarea',
                    'name' => 'keywords',
                    'label' => 'Meta Keywords',
                    'value' => $seo->keywords ?? '',
                    'col' => 'col-md-6 mb-3',
                ],
                [
                    'type' => 'text',
                    'name' => 'canonical',
                    'label' => 'Canonical URL',
                    'value' => $seo->canonical ?? '',
                    'col' => 'col-md-6 mb-3',
                ],
            ])
            ->endRow()

            // ADVANCED SEO
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'text',
                    'name' => 'meta_title',
                    'label' => 'Meta Title',
                    'value' => $seo->seo_settings['meta_title'] ?? '',
                    'col' => 'col-md-6 mb-3',
                ],
                [
                    'type' => 'textarea',
                    'name' => 'meta_description',
                    'label' => 'Meta Description',
                    'value' => $seo->seo_settings['meta_description'] ?? '',
                    'col' => 'col-md-6 mb-3',
                ],
            ])
            ->endRow()

            // Twitter
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'text',
                    'name' => 'twitter_handle',
                    'label' => 'Twitter Handle',
                    'value' => $seo->seo_settings['twitter_handle'] ?? '',
                    'col' => 'col-md-6 mb-3',
                ],
                [
                    'type' => 'text',
                    'name' => 'twitter_creator',
                    'label' => 'Twitter Creator',
                    'value' => $seo->seo_settings['twitter_creator'] ?? '',
                    'col' => 'col-md-6 mb-3',
                ],
            ])
            ->endRow()

            // Verification
            ->startRow()
            ->addFormFields([
                ['type' => 'text', 'name' => 'google_verification', 'label' => 'Google Verification', 'value' => $seo->seo_settings['google_verification'] ?? '', 'col' => 'col-md-4 mb-3'],
                ['type' => 'text', 'name' => 'bing_verification', 'label' => 'Bing Verification', 'value' => $seo->seo_settings['bing_verification'] ?? '', 'col' => 'col-md-4 mb-3'],
                ['type' => 'text', 'name' => 'yandex_verification', 'label' => 'Yandex Verification', 'value' => $seo->seo_settings['yandex_verification'] ?? '', 'col' => 'col-md-4 mb-3'],
            ])
            ->endRow()

            // Geo
            ->startRow()
            ->addFormFields([
                ['type' => 'text', 'name' => 'geo_region', 'label' => 'Geo Region', 'value' => $seo->seo_settings['geo_region'] ?? '', 'col' => 'col-md-4 mb-3'],
                ['type' => 'text', 'name' => 'geo_placename', 'label' => 'Geo Place', 'value' => $seo->seo_settings['geo_placename'] ?? '', 'col' => 'col-md-4 mb-3'],
                ['type' => 'text', 'name' => 'geo_position', 'label' => 'Geo Position', 'value' => $seo->seo_settings['geo_position'] ?? '', 'col' => 'col-md-4 mb-3'],
            ])
            ->endRow()

            // Organization
            ->startRow()
            ->addFormFields([
                ['type' => 'text', 'name' => 'organization_name', 'label' => 'Organization Name', 'value' => $seo->seo_settings['organization_name'] ?? '', 'col' => 'col-md-6 mb-3'],
                ['type' => 'text', 'name' => 'organization_logo', 'label' => 'Organization Logo', 'value' => $seo->seo_settings['organization_logo'] ?? '', 'col' => 'col-md-6 mb-3'],
            ])
            ->endRow()

            // Social
            ->startRow()
            ->addFormFields([
                ['type' => 'text', 'name' => 'facebook_url', 'label' => 'Facebook URL', 'value' => $seo->seo_settings['facebook_url'] ?? '', 'col' => 'col-md-6 mb-3'],
                ['type' => 'text', 'name' => 'youtube_url', 'label' => 'YouTube URL', 'value' => $seo->seo_settings['youtube_url'] ?? '', 'col' => 'col-md-6 mb-3'],
            ])
            ->endRow()

            // Image (with preview note if your builder supports it)
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'file',
                    'name' => 'image',
                    'label' => 'Seo Image',
                    'col' => 'col-md-12 mb-3',
                ]
            ])
            ->endRow()

            // Status
            ->startRow()
            ->addFormFields([
                [
                    'type' => 'radio',
                    'name' => 'status',
                    'label' => 'Status',
                    'options' => [
                        ['value' => 'active', 'label' => 'Active', 'checked' => ($seo->status == 'active')],
                        ['value' => 'inactive', 'label' => 'Inactive', 'checked' => ($seo->status == 'inactive')],
                    ],
                    'required' => true,
                    'col' => 'col-md-6 mb-3',
                ]
            ])
            ->endRow()

            // Submit
            ->startRow()
            ->addInput([
                'type' => 'submit',
                'value' => 'Update SEO',
                'class' => 'btn btn-primary btn-sm',
            ])
            ->endRow()

            ->build();

        return view('Seo::seo.edit')->with([
            'title' => 'SEO',
            'buttons' => $buttons,
            'formConfig' => $formConfig,
            'seo' => $seo
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'title' => ['required', Rule::unique('seos')->ignore(1)],
            'short_description' => 'required|max:300',
            'description' => 'required|string',
            'keywords' => 'nullable|string|max:255',
            'canonical' => 'nullable|url|max:255',
            'image' => 'nullable|image',
            'status' => 'required|in:active,inactive',
        ]);

        $id = 1;

        try {
            $seo = $this->service->find($id);

            $data = $request->only([
                'title',
                'short_description',
                'description',
                'keywords',
                'canonical',
                'status',
            ]);

            $data['slug'] = Str::slug($request->title);

            /**
             * 🔥 IMPORTANT: ADVANCED SEO JSON UPDATE
             */
            $data['seo_settings'] = [
                'meta_title' => $request->meta_title,
                'meta_description' => $request->meta_description,

                'twitter_handle' => $request->twitter_handle,
                'twitter_creator' => $request->twitter_creator,

                'google_verification' => $request->google_verification,
                'bing_verification' => $request->bing_verification,
                'yandex_verification' => $request->yandex_verification,

                'geo_region' => $request->geo_region,
                'geo_placename' => $request->geo_placename,
                'geo_position' => $request->geo_position,

                'organization_name' => $request->organization_name,
                'organization_logo' => $request->organization_logo,

                'facebook_url' => $request->facebook_url,
                'youtube_url' => $request->youtube_url,

                'gtmId' => $request->gtmId ,
                'fbPixelId' => $request->fbPixelId ,
                'clarityId' => $request->clarityId ,
            ];


            /**
             * IMAGE UPDATE
             */
            if ($request->hasFile('image')) {

                if ($seo->image && File::exists(public_path($seo->image))) {
                    File::delete(public_path($seo->image));
                }

                $file = $request->file('image');
                $name = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads/seo'), $name);

                $data['image'] = 'uploads/seo/' . $name;
            }

            $this->service->update($id, $data);

            return redirect()->route('seo.information')->with('success', 'Updated');

        } catch (\Throwable $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Failed');
        }
    }
}
