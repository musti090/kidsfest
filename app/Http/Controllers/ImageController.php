<?php

namespace App\Http\Controllers;

use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;
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
    

	  public function testEdim(Request $request){

	        $fin_code = $request->fin_code;
           $serial_number = $request->serial_number;

            
                $base64String = Http::withHeaders(['X-Bridge-Authorization' => env('ASAN_TOKEN')])
                    ->get(env('ASAN_URL') . '?documentNumber=' . $serial_number . '&fin=' . $fin_code)->json()['data'][0]['personAz']['images'][0]['imageStream'] ?? null;
                    
                    

            $photo = null;
            if ($base64String) {
                
                          $fileService = new FileService();
                $storagePath =  "uif/person";
                $photo = $fileService->uploadBase64($base64String, $storagePath);
            }

                    
                    
                    return 88;
 

	  }


}
