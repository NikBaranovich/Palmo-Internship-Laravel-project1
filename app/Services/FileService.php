<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class FileService extends BaseService
{
    public function __construct()
    {
    }

    public function save($request, $filename, $path, $disk)
    {
        if (!$request->hasFile($filename)) {
            return;
        }
        $imagePath = $request->file($filename)->store($path, $disk);

        return $imagePath;
    }

    public function download($request, $filename, $headers)
    {
        $path = storage_path('app/' . $request
            ->input('filepath'));

        return response()->download($path, $filename, $headers, 'inline');
    }

    public function delete($disk, $path)
    {
        if (!$path) {
            return;
        }
        
        return Storage::disk($disk)->delete($path);
    }
}
