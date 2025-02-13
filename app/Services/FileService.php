<?php

namespace App\Services;

use App\Models\File;
use App\Utils\Ftp\UploadUtil;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class FileService
{

    public function singleFileUpload($file)
    {
//        $fileSize = $file->getSize();
//        $mimeType = $file->getMimeType();
//        $ext = $file->getClientOriginalExtension();

        $path = "uif/" . date('Y') . '/' . date('m');
        $storagePath = 'public/' . $path;

        $filename = uniqid() . '.' . $file->getClientOriginalExtension();

        $ftpUtil = new UploadUtil();
        $ftpUtil->upload($file, $filename, $storagePath);

        return 22;

//        $model = File::query()->create([
//            "storage_path" => $path . '/' . $filename, #public/files/a.png
//            "path" => $path . '/' . $filename,
//            "extension" => $ext,
//            "size" => $fileSize,
//            "mime_type" => $mimeType
//        ]);
//
//        return $model;
    }

}
