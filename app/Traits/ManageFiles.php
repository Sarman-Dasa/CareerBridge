<?php

namespace App\Traits;

use Illuminate\Support\Facades\File;

trait ManageFiles
{
    public function uploadFile($file, $directory, $is_audio = false)
    {
        $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $file_exe = $is_audio ? 'mp3' :  $file->extension();
        $fileName = $originalName . '_' . time() . '.' . $file_exe;
        $filePath = $file->move($directory, $fileName);
        return $filePath;
    }

    public function deleteFile($filePath)
    {
        $file = public_path($filePath);
        $result = File::delete($file);
        return $result;
    }
}
