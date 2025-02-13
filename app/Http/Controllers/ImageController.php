<?php

namespace App\Http\Controllers;

use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{

    public $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }
    public function index()
    {
        return view('welcome');
    }

    public function store(Request $request)
    {
        if($request->hasFile('profile_image')) {

            try {
                return $this->fileService->singleFileUpload($request->file("profile_image"));

            } catch (\Exception $e) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
        }

        return redirect('image')->with('success', "Image uploaded successfully.");
    }

}
