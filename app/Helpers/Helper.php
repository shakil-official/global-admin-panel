<?php


use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;


if (!function_exists('actionDropdown')) {
    function actionDropdown($id, $editRoute, $viewRoute = null): string
    {
        return '<div class="dropdown d-inline-block">
                    <button class="btn btn-soft-secondary btn-sm dropdown"
                            type="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">
                                <i class="ri-more-fill align-middle"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        ' . ($viewRoute ? '<li>
                                                <a href="' . $viewRoute . '" class="dropdown-item">
                                                    <i class="ri-eye-fill align-bottom me-2 text-muted"></i>View
                                                </a>
                                            </li>' : '') . '<li>
                            <a href="' . $editRoute . '" class="dropdown-item edit-item-btn">
                                <i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit
                            </a>
                        </li>
                        ' . ('<li><button class="dropdown-item remove-item-btn" id="deleteButton" data-id="' . $id . '" ><i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete</button></li>') . '
                    </ul>
                </div>';
    }
}


if (!function_exists('actionDropdownForOrder')) {
    function actionDropdownForOrder($id, $editRoute, $viewRoute = null): string
    {
        return '<div class="dropdown d-inline-block">
                    <button class="btn btn-soft-secondary btn-sm dropdown"
                            type="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">
                                <i class="ri-more-fill align-middle"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        ' . ($viewRoute ? '<li>
                                                <a href="' . $viewRoute . '" class="dropdown-item">
                                                    <i class="ri-eye-fill align-bottom me-2 text-muted"></i>View
                                                </a>
                                            </li>' : '') . '<li>
                            <a href="' . $editRoute . '" class="dropdown-item edit-item-btn">
                                <i class="ri-pencil-fill align-bottom me-2 text-muted"></i> Edit
                            </a>
                        </li>
                    </ul>
                </div>';
    }
}


function fileUploadRequest(Request $request, $path = 'upload/extra/'): Request
{
    if (!is_null($request->file('file'))) {
        $file = uploadFile($request->file('file'), $path . Carbon::today()->format('d-m-Y'));
        $request['upload'] = $file['path'];
        $request['mime_type'] = $file['mime_type'];
        $request['file_size'] = $file['file_size'];
        $request['file_name'] = $file['file_name'];
    } else {
        $request['upload'] = $request->input('old-upload');
    }

    return $request;
}


function uploadFile($fileInput, $path, $customName = null): ?array
{
    // dd('test', $fileInput->getError(), $fileInput->getClientOriginalName(), $fileInput->getSize());
    // Check if the file input is valid
    if ($fileInput->isValid()) {
        // Get the file size of the uploaded file
        $fileSize = $fileInput->getSize();

        // Extract the original file extension
        $extension = $fileInput->getClientOriginalExtension();

        // Generate a unique file name or use the custom name if provided
        $fileName = $customName ? $customName . '.' . $extension : Str::random(20) . '.' . $extension;

        // Determine the full path where the file will be stored
        $filePath = public_path($path);

        // Create the directory if it doesn't exist
        if (!File::exists($filePath)) {
            File::makeDirectory($filePath, 0777, true, true);
        }

        // Move the file to the specified path
        $fileInput->move($filePath, $fileName);

        // Set permissions for the uploaded file
        chmod($filePath . '/' . $fileName, 0664);

        // Get the public URL of the uploaded file
        $publicUrl = asset($path . '/' . $fileName);

        // Get the MIME type of the uploaded file
        $mimeType = $fileInput->getClientMimeType();

        // Return the relative path and full public URL
        return [
            'path' => $path . '/' . $fileName,
            'url' => $publicUrl,
            'mime_type' => $mimeType,
            'file_size' => $fileSize,
            'file_name' => $fileName,
        ];
    }
    // Return null if the file upload failed
    return null;
}

function deleteFile($filePath): bool
{
    if (File::exists(public_path($filePath))) {
        // Delete the file
        File::delete(public_path($filePath));
        // Return true indicating successful deletion
        return true;
    }

    // Return false if the file doesn't exist
    return false;
}

// Function to get status message


function transformArrayValues(array $keys): array
{
    return array_map(function ($key) {
        // Remove special characters and replace underscores or hyphens with spaces
        $key = preg_replace('/[^a-zA-Z0-9_\-]/', '', $key); // Remove non-alphanumeric or underscore/hyphen
        $key = str_replace(['_', '-'], ' ', $key); // Replace underscores or hyphens with spaces

        // Capitalize each word
        return ucwords($key);
    }, $keys);
}


function imageReturn($data)
{
    if (isset($data[0])) {
        return $data[0]->image;
    }

    return '';
}


if (!function_exists('actionDropdownWithOutEdit')) {
    function actionDropdownWithOutEdit($id, $deleteRoute = null): string
    {
        return '<div class="dropdown d-inline-block">
                    <button class="btn btn-soft-secondary btn-sm dropdown"
                            type="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">
                                <i class="ri-more-fill align-middle"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        </li>
                      ' . ('<li><button class="dropdown-item remove-item-btn" id="deleteButton" data-id="' . $id . '" ><i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete</button></li>') . '
                    </ul>
                </div>';
    }
}

if (!function_exists('actionDropdownView')) {
    function actionDropdownView($id, $viewRoute = null): string
    {
        return '
        <div class="dropdown d-inline-block">
            <button class="btn btn-soft-secondary btn-sm dropdown"
                    type="button"
                    data-bs-toggle="dropdown"
                    aria-expanded="false">
                <i class="ri-more-fill align-middle"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                ' .
            ($viewRoute ? '
                <li>
                    <a href="' . $viewRoute . '" class="dropdown-item">
                        <i class="ri-eye-fill align-bottom me-2 text-muted"></i> View
                    </a>
                </li>' : '') . '
                <li>
                    <button class="dropdown-item remove-item-btn"
                            id="deleteButton"
                            data-id="' . $id . '">
                        <i class="ri-delete-bin-fill align-bottom me-2 text-muted"></i> Delete
                    </button>
                </li>
            </ul>
        </div>';
    }
}


if (!function_exists('actionDropdownStatus')) {
    function actionDropdownStatus($status): string
    {
        if ($status == 'active'){
            return '<span class="badge bg-info-subtle text-primary">Active</span>';
        }

        if ($status == 'inactive'){
            return '<span class="badge bg-danger-subtle text-danger">Active</span>';
        }

        return '<span class="badge bg-secondary-subtle text-secondary">Unknown</span>';

    }
}



if (!function_exists('badge')) {
    function badge($value, $status): string
    {
        $badges = [
            'primary' => '<span class="badge bg-primary-subtle text-primary">' . $value . '</span>',
            'secondary' => '<span class="badge bg-secondary-subtle text-secondary">' . $value . '</span>',
            'success' => '<span class="badge bg-success-subtle text-success">' . $value . '</span>',
            'danger' => '<span class="badge bg-danger-subtle text-danger">' . $value . '</span>',
            'warning' => '<span class="badge bg-warning-subtle text-warning">' . $value . '</span>',
            'info' => '<span class="badge bg-info-subtle text-info">' . $value . '</span>',
            'light' => '<span class="badge bg-light-subtle text-dark">' . $value . '</span>',
            'dark' => '<span class="badge bg-dark-subtle text-white">' . $value . '</span>',
            'active' => '<span class="badge bg-success-subtle text-success">' . $value . '</span>',
            'inactive' => '<span class="badge bg-danger-subtle text-danger">' . $value . '</span>',
            'pending' => '<span class="badge bg-warning-subtle text-warning">' . $value . '</span>',
            'premium' => '<span class="badge bg-warning-subtle text-warning">' . $value . '</span>',
            'specialized' => '<span class="badge bg-info-subtle text-info">' . $value . '</span>',
            'value_added' => '<span class="badge bg-primary-subtle text-primary">' . $value . '</span>',
        ];

        return $badges[strtolower($status)] ?? '<span class="badge bg-secondary-subtle text-secondary">' . $value . '</span>';
    }
}
