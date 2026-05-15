<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\About;
use App\Models\Contact;
use App\Models\FormStore;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Support\Facades\Mail;

class AboutController extends Controller
{
    /**
     * Fetch terms content.
     *
     * @return JsonResponse
     */
    public function getAbout(): JsonResponse
    {
        // Example terms content (you can load this from a database or file)
        $term = About::query()->latest()->first();
        return response()->json([
            'Title' => $term->title ?? '',
            'htmlContent' => $term->description ?? 'No content available.',
        ], 200);

    }

    public function insertContact(Request $request): JsonResponse
    {
        // Validate the request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required_without:email|nullable|string|max:15',
            'address' => 'nullable|string|max:255',
            'area' => 'nullable|string|max:255',
            'description' => 'required|string|max:1000',
        ], [
            'description.required' => 'Message is required.',
        ]);

        $validatedData['email'] = request('email');


        try {
            // Create the contact
            Contact::query()->create($validatedData);


            return response()->json([
                'success' => true,
                'message' => 'Message sent.',
                'data' => [],
            ], 201);
        } catch (\Exception $e) {
            // Handle any errors
            return response()->json([
                'success' => false,
                'message' => 'Failed to create contact.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    private function formDataStore(Request $request, $extra)
    {
        return FormStore::query()->create([
            'name'       => $request->input('name'),
            'mobile'     => $request->input('mobile'),
            'address'    => $request->input('address'),
            'type'       => $request->input('type'),
            'extra_data' => json_encode($extra),
            'message'    => $request->input('message'),
            'package'    => $request->input('package'),
            'status'     => $request->input('status', 'pending'),
            'source'     => $request->input('source', FormStore::SOURCE_PACKAGE),
        ]);
    }

    public function connectionRequest(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'message' => 'nullable|string',
        ]);

        $extra = [
          'preferredDate' => $request->input('preferredDate', null),
          'mapLink' => $request->input('mapLink', null),
        ];

        $formStore = $this->formDataStore($request, $extra);

        return response()->json([
            'success' => true,
            'message' => 'Connection request submitted successfully.',
            'data' => $formStore,
        ]);
    }

}
