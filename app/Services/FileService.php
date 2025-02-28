<?php

namespace App\Services;

use App\Models\File;
use App\Utils\Ftp\UploadUtil;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class FileService
{
    public function singleFileUpload($file,$type = null)
    {
        $type = $type == 1 ? "personal" : "collective";
        $path = "uif/" . date('Y') . "/".$type;
        $storagePath = 'public/' . $path;
        $filename = uniqid() . time() . rand(1, 100) . uniqid() . "." . $file->getClientOriginalExtension();
        $ftpUtil = new UploadUtil();
        $ftpUtil->upload($file, $filename, $storagePath);
        return $path."/".$filename;
    }


    public function singleBase64Image($base64String,$type = null)
    {
        $type = $type == 1 ? "personal" : "collective";
        $path = "uif/" . date('Y') . "/".$type;
        $storagePath = 'public/' . $path;
        $decodedImage = base64_decode($base64String);
        $fileExtension = (explode('/', finfo_buffer(finfo_open(), $decodedImage, FILEINFO_MIME_TYPE))[1]);
        $filename = uniqid() . time() . rand(1, 100) . uniqid() . "." .$fileExtension;
        $ftpUtil = new UploadUtil();
        $ftpUtil->uploadBase64($base64String, $filename, $storagePath);
        return $path."/".$filename;
    }

}
