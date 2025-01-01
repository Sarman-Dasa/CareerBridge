<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

trait ManageFiles
{
    public function uploadFile($file, $directory, $is_audio = false)
    {
        // Validate file extension (audio or regular files)
        $file_exe = $is_audio ? 'mp3' : $file->extension();

        // Generate unique file name
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $fileName = $originalName . '_' . time() . '.' . $file_exe;

        // Store the file in the 'public' disk, which will save the file in 'storage/app/public'
        // $filePath = $file->storeAs($directory, $fileName, 'public');

        // Store the file in the 'public' disk
        $filePath = Storage::disk('public')->putFileAs($directory, $file, $fileName);
        return $filePath;
    }

    public function deleteFile($filePath)
    {
        // $file = public_path($filePath);
        // Attempt to delete the file from the 'public' disk
        $result = Storage::disk('public')->delete($filePath);
        //return $result;
    }
}
