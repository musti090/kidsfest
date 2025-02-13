<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\FileService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class FileUploadController extends Controller
{
    public $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }

    public function singleFileUpload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => [
                'required',
                'mimes:jpg,jpeg,png,doc,docx,pdf,zip,xlsx,xls',
                'max:51200',
                'extensions:jpg,jpeg,png,doc,docx,pdf,zip,xlsx,xls'
            ]
        ]);

        if ($validator->fails()) {
            return validationResp($validator);
        }

        return $this->fileService->singleFileUpload($request->file("file"), $request->is_private);
    }

    public function multiFileUpload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'files' => 'required',
            'files.*' => [
                'required',
                'mimes:jpg,jpeg,png,doc,docx,pdf,zip,xlsx,xls',
                'max:51200',
                'extensions:jpg,jpeg,png,doc,docx,pdf,zip,xlsx,xls'
            ]
        ]);

        if ($validator->fails()) {
            return validationResp($validator);
        }

        return $this->fileService->multiFileUpload($request->file("files"), $request->is_private);
    }

    private function createNewFileName(string $ext)
    {
        return strtolower(Str::random(10) . '_' . time() . '.' . $ext);
    }

}
