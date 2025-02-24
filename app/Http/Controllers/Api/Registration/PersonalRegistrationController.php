<?php

namespace App\Http\Controllers\Api\Registration;

use App\Events\PersonalUserAdded;
use App\Http\Controllers\Controller;
use App\Http\Requests\PersonalRegistrationFirstStepRequest;
use App\Http\Requests\PersonalRegistrationSecondStepRequest;
use App\Http\Requests\PersonalRegistrationThirdStepRequest;
use App\Http\Requests\PersonalUserRequest;
use App\Models\Award;
use App\Models\PersonalUser;
use App\Models\PersonalUserCardInformation;
use App\Models\PersonalUserParent;
use App\Services\FileService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipStream\Exception;
use Illuminate\Support\Facades\Validator;

class PersonalRegistrationController extends Controller
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
                'mn_region_id' => 'required|numeric',
                'live_address' => 'required|string'

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
    public function secondStep(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'city_id' => 'required|numeric',
                'school_type_id' => 'required|numeric',
                'school_id' => 'required|numeric',
                'nomination_id' => 'required|numeric',
                'art_type' => 'required|numeric',
                'art_education' => 'nullable|string',
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
    public function thirdStep(Request $request)
    {

        try {
            $validator = Validator::make($request->all(), [
                'parent_name' => 'required|string|max:50',
                'parent_surname' => 'required|string|max:50',
                'parent_patronymic' => 'required|string|max:50',
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
            // Şəkil yükləməsi
            $imagePath = null;
            if ($request->has('photo') && $request->image_type == 1) {
                $base64String = $request->photo;
                $imagePath = $this->fileService->singleBase64Image($base64String);
            } elseif ($request->hasFile('photo') && $request->image_type == 2) {
                $imagePath = $this->fileService->singleFileUpload($request->file("photo"));
            }

            // Verilənləri insert etmək
            $user_id = DB::table('personal_users')->insertGetId([

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
                'photo' => $imagePath,
                'year' => date('Y'),
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Təltif əlavə etmək
            if ($request->has('awards_name')) {
                $awardsData = [];
                foreach ($request->awards_name as $awards_name) {
                    $awardsData[] = [
                        'personal_user_id' => $user_id,
                        'awards_name' => $awards_name ?? null,
                        'year' => date('Y'),
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
                DB::table('personal_awards')->insert($awardsData);
            }
            $endDate = env('END_DATE');
            $dateOfBirth = $request->birth_date;
            $diff = date_diff(date_create($dateOfBirth), date_create($endDate))->format('%y');
            // Personal User Card Information əlavə etmək
            $UIN = date('Y') . ($user_id + 1000000);
             DB::table('personal_user_card_information')->insert([
                'personal_user_id' => $user_id,
                'all_city_id' => $request->city_id,
                'age' => $diff,
                'fin_code' => $request->fin_code,
                'card_old_or_new' => $request->card_old_or_new,
                'serial_number' => $request->serial_number,
                'year' => date('Y'),
                'created_at' => now(),
                'updated_at' => now(),
                'UIN' => $UIN,
            ]);
            // Parent Məlumatlarını Əlavə Etmək
            DB::table('personal_user_parents')->insert([
                'personal_user_id' => $user_id,
                'parent_name' => $request->parent_name,
                'parent_surname' => $request->parent_surname,
                'parent_patronymic' => $request->parent_patronymic,
                'parent_fin_code' => $request->parent_fin_code,
                'parents_card_old_or_new' => $request->parents_card_old_or_new,
                'parent_serial_number' => $request->parent_serial_number,
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
