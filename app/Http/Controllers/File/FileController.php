<?php


namespace App\Http\Controllers\File;

use App\Http\Controllers\Controller;
use App\Models\ImageTemp;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\ImageFile;

class FileController extends Controller
{
    public function upload(Request $request): JsonResponse
    {
        // Validation for file upload
        $validator = Validator::make($request->all(), [
            'file.*' => 'required|mimes:jpg,jpeg,png,pdf|max:2048', // File type and size validation
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $uploadedFiles = null;

        if ($request->file('file')) {
            $file = $request->file('file');
            $date = now()->format('Y-m-d'); // Get the current date in 'YYYY-MM-DD' format

            // Define the destination path inside the public/uploads folder
            $destinationPath = public_path("uploads/{$date}");

            // Ensure the directory exists
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0775, true); // Create the directory if it doesn't exist
            }

            // Get the file's original name and move it to the destination path
            $fileName = $date . '_' . uniqid() . '_' . $file->getClientOriginalName();
            $file->move($destinationPath, $fileName);

            // The file path in public folder
            $filePath = "uploads/{$date}/" . $fileName;

            // Save the file details to the database
            $uploadedFiles = [
                'file_name' => $fileName,
                'file_extension' => $file->getClientOriginalExtension(),
                'file_path' => $filePath,
                'file_url' => url($filePath), // URL to access the file
                'file_id' => pathinfo($filePath, PATHINFO_FILENAME) // Using filename as file ID
            ];

            // Store in ImageTemp model
            ImageTemp::query()->create([
                'name' => $filePath,
            ]);
        }

        return response()->json([
            'message' => 'Files uploaded successfully',
            'uploaded_files' => $uploadedFiles // Return uploaded files data (including IDs)
        ]);
    }

    public function delete(Request $request): JsonResponse
    {
        $filePath = null;

        if (!is_null($request->get('id'))) {
            $productImage = ImageTemp::query()
                ->where([
                    'id' => $request->get('id'),
                ])->first();

            if ($productImage) {
                $filePath = public_path($productImage->image); // Adjust for public folder path
            }
        } else {
            // If no ID is provided, directly get the file path from the request
            $filePath = public_path($request->input('file_path')); // Adjust for public folder path
        }

        // Check if the file path is valid and the file exists
        if (!is_null($filePath) && file_exists($filePath)) {
            unlink($filePath); // Delete the file from the public directory

            // If an ID is provided, delete the corresponding database record
            if (!is_null($request->get('id'))) {
                ImageTemp::query()
                    ->where([
                        'id' => $request->get('id'),
                    ])->delete();
            }

            return response()->json(['message' => 'File deleted successfully']);
        }

        ImageTemp::query()
            ->where([
                'id' => $request->get('id'),
            ])->delete();

        return response()->json(['error' => 'File not found'], 404);
    }
}
