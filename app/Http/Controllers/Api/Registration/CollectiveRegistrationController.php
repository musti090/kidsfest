<?php

namespace App\Http\Controllers\Api\Registration;

use App\Events\CollectiveAdded;
use App\Http\Controllers\Controller;
use App\Http\Requests\CollectiveRegistrationFirstStepRequest;
use App\Http\Requests\CollectiveRegistrationSecondStepRequest;
use App\Http\Requests\CollectiveRegistrationThirdStepRequest;
use App\Models\Award;
use App\Models\Collective;
use App\Models\CollectiveAward;
use App\Models\CollectiveDirector;
use App\Models\CollectiveImage;
use App\Models\CollectiveTeenager;
use App\Models\PersonalUser;
use App\Models\PersonalUserCardInformation;
use App\Models\PersonalUserParent;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class CollectiveRegistrationController extends Controller
{
    public $fileService;

    public function __construct(FileService $fileService)
    {
        $this->fileService = $fileService;
    }
    public function firstStep(Request $request)
    {
        try {

            $validator = Validator::make($request->all(), [
                'collective_name' => 'required|string|max:150',
                'collective_created_date' => 'required|numeric|digits:4|between:1900,'.date('Y'),
                'collective_mn_region_id' => 'required|numeric',
                'collective_city_id' => 'required|numeric',
                'collective_nomination_id' => 'required|numeric',
                'awards_name' => 'nullable|array',
                'awards_name.*' => 'string|max:255'
            ]);
            if ($validator->fails()) {
                return response()->json(["errors" => $validator->messages()], 422);
            }
            return response(['message' => 'OK'], 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function secondStep(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                "photo" => [
                    'required_if:image_type,2',
                    function (string $attribute, mixed $value, \Closure $fail) use ($request) {
                        if ($request->image_type == 2) {
                            // Faylın yüklənib-yüklənmədiyini yoxla
                            if (!$request->hasFile('photo')) {
                                $fail("Foto yüklənə bilən fayl olmalıdır!");
                                return;
                            }

                            $file = $request->file('photo');

                            // Bütün şəkil formatlarını qəbul etmək üçün `image/*` MIME yoxlaması
                            if (!str_starts_with($file->getMimeType(), 'image/')) {
                                $fail("Şəkil formatı düzgün deyil!");
                                return;
                            }

                            // Fayl ölçüsünü məhdudlaşdır (Məsələn, maksimum 5MB)
                            if ($file->getSize() > 5 * 1024 * 1024) {
                                $fail("Şəklin həcmi 5MB dan çox ola bilməz!");
                            }
                        }
                    },
                ],
                'name' => 'required|string|max:50',
                'surname' => 'required|string|max:50',
                'patronymic' => 'required|string|max:50',
                'gender' => 'required|string|max:10',
                'birth_date' => 'required|date_format:Y-m-d',
                'registration_address' => 'required|string',
                'city_id' => 'required|numeric',
                'live_address' => 'required|string',
                'art_type' => 'required|numeric',
                'art_education' => 'nullable|string',
            ], [
                'photo.required_if' => 'Foto mütləqdir',
                'birth_date.date_format' => 'Doğum tarixi gün.ay.il formatında olmalıdır',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            $dateOfBirth = $request->birth_date;
            $endDate = env('END_DATE');
            $diff = date_diff(date_create($dateOfBirth), date_create($endDate))->format('%y');
            if ($diff >= 18 || $diff <= 5) {
                return response(['message' => 'Yaşınız uyğun deyil!'], 422);
            }
            return response(['message' => 'OK'], 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

	  public function check(Request $request){

		 if($request->has('nomination_id') && $request->has('children_count')){

			 if($request->nomination_id == 19 && ($request->children_count > 20 || $request->children_count < 4)){

				   return response(['message' => 'Sizin müraciətiniz iştirakçı sayı limitinə uyğun deyil.Milli rəqs (ansambl) nominasiyası üzrə limit 4-20 nəfər arası' ], 422);
			 }
			  elseif($request->nomination_id == 20 && ($request->children_count > 20 || $request->children_count < 4)){

				   return response(['message' => 'Sizin müraciətiniz iştirakçı sayı limitinə uyğun deyil.Müasir rəqs (ansambl) nominasiyası üzrə limit 4-20 nəfər arası' ], 422);
			 }
			  elseif($request->nomination_id == 21 && ($request->children_count > 4 || $request->children_count < 2)){

				   return response(['message' => 'Sizin müraciətiniz iştirakçı sayı limitinə uyğun deyil.Bal rəqsi üzrə limit 2-4 nəfər arası' ], 422);
			 }
			  elseif($request->nomination_id == 22 && ($request->children_count > 5 || $request->children_count < 3)){

				   return response(['message' => 'Sizin müraciətiniz iştirakçı sayı limitinə uyğun deyil.Caz ifaçılığı üzrə limit 3-5 nəfər arası' ], 422);
			 }
             elseif($request->nomination_id == 23 && ($request->children_count > 20 || $request->children_count < 12)){

                 return response(['message' => 'Sizin müraciətiniz iştirakçı sayı limitinə uyğun deyil.Xor üzrə limit 12-20 nəfər arası' ], 422);
             }
             else{
                 return response(['message' => 'OK'], 200);
             }

		 }
	  }







    public function thirdStep(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'director_name' => 'required|string|max:50',
                'director_surname' => 'required|string|max:50',
                'director_patronymic' => 'required|string|max:50',
                'first_prefix' => 'required|numeric',
                'second_prefix' => 'required|numeric',
                'first_phone_number' => 'required|numeric|digits:7',
                'second_phone_number' => 'required|numeric|digits:7',
                'email' => 'required|email'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return response(['message' => 'OK'], 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function store(Request $request)
    {

        $UIN = null;
        DB::transaction(function () use ($request, &$UIN) {

            $collective_id = DB::table('collectives')->insertGetId([
                'collective_name' => $request->collective_name,
                'collective_created_date' => $request->collective_created_date,
                'collective_nomination_id' => $request->collective_nomination_id,
                'collective_mn_region_id' => $request->collective_mn_region_id,
                'collective_city_id' => $request->collective_mn_region_id,
                'year' => date('Y'),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Təltif əlavə etmək
            if ($request->has('awards_name')) {
                $awardsData = [];
                foreach ($request->awards_name as $awards_name) {
                    $awardsData[] = [
                        'collective_id' => $collective_id,
                        'awards_name' => $awards_name ?? null,
                        'year' => date('Y'),
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
                DB::table('collective_awards')->insert($awardsData);
            }





            if ($request->has('key')) {
                foreach ($request->key as $key => $value) {

                    // Şəkil yükləməsi
                    $imagePath = null;
                    if ($value['image_type'] == 1) {
                        $base64String = $value['photo'];
                        $imagePath = $this->fileService->singleBase64Image($base64String);
                    } elseif ($value['image_type']  == 2) {
                        $imagePath = $this->fileService->singleFileUpload($value['photo']);
                    }

                    $endDate = env('END_DATE');
                    $dateOfBirth = $value['birth_date'];
                    $diff = date_diff(date_create($dateOfBirth), date_create($endDate))->format('%y');
                    DB::table('collective_teenagers')->insert([
                        'collective_id' => $collective_id,
                        'group_number' => $key + 1,
                        'photo' => $imagePath,
                        'name' => $request->name,
                        'surname' => $request->surname,
                        'patronymic' => $request->patronymic,
                        'birth_date' => $request->birth_date,
                        'registration_address' => $request->registration_address,
                        'live_address' => $request->live_address,
                        'gender' => $request->gender,
                        'mn_region_id' => $request->mn_region_id,
                        'school_type_id' => $request->school_type_id,
                        'school_id' => $request->school_id,
                        'nomination_id' => $request->nomination_id,
                        'art_type' => $request->art_type,
                        'art_education' => $request->art_education,
                        'age' => $diff,
                        'year' => date('Y'),
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                }
            }

            $UIN = date('Y') . ($collective_id + 2000000);
            // Direktor Məlumatlarını Əlavə Etmək
            DB::table('collective_directors')->insert([
                'collective_id' => $collective_id,
                'UIN' => $UIN,
                'director_name' => $request->director_name,
                'director_surname' => $request->director_surname,
                'director_patronymic' => $request->director_patronymic,
                'director_fin_code' => $request->director_fin_code,
                'directors_card_old_or_new' => $request->directors_card_old_or_new,
                'director_serial_number' => $request->director_serial_number,
                'first_prefix' => $request->first_prefix,
                'first_phone_number' => $request->first_phone_number,
                'second_prefix' => $request->second_prefix,
                'second_phone_number' => $request->second_phone_number,
                'email' => $request->email,
                'year' => date('Y'),
                'created_at' => now(),
                'updated_at' => now()
            ]);

        });
           return response(['message' => $UIN ?? 'Sistem xətası !!!'], 201);
    }
}
