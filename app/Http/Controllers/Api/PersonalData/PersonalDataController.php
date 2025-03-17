<?php

namespace App\Http\Controllers\Api\PersonalData;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PersonalDataController extends Controller
{
    public function getPersonalData(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'fin_code' => 'bail|required|string|size:7',
                "serial_number" => [
                    'bail',
                    'required',
                    'numeric',
                    function (string $attribute, mixed $value, \Closure $fail) use ($request) {
                        if ($request->card_old_or_new == 1 and Str::length($request->serial_number) != 7) {
                            $fail("Seriya AA olduğu üçün seriya nömrəsi 7 rəqəmdən ibarət olmalıdır");
                        }
                        if ($request->card_old_or_new == 2 and Str::length($request->serial_number) != 8) {
                            $fail("Seriya AZE olduğu üçün seriya nömrəsi 8 rəqəmdən ibarət olmalıdır");
                        }
                    },
                ],
                'card_old_or_new' => 'bail|required|numeric|in:1,2'
            ]);
            if ($validator->fails()) {
                return response()->json(["errors" => $validator->messages()], 422);
            }
            $fin_code = $request->fin_code;
            $serial_number = $request->serial_number;
            if ($request->card_old_or_new != 2) {
                $serial_number = "AA" . $serial_number;
            }
            $data = Http::withHeaders(['X-Bridge-Authorization' => env('ASAN_TOKEN')])
                ->get(env('ASAN_URL') . '?documentNumber=' . $serial_number . '&fin=' . $fin_code)->json()['data'];
            $endDate = env('END_DATE');
            $date = date('Y-m-d');
            if (is_null($data)) {
                return response(['message' => 'FİN kod və ya seriya nömrəsi yanlışdır!'], 422);
            } elseif (empty($data)) {
                return response(['message' => 'Məlumat tapılmadı! Zəhmət olmasa məlumatları əl ilə daxil edin.', 'code' => 2], 404);
            } elseif ($data[0]['expiryDate']['date'] < $date) {
                return response(['message' => 'Sənədin etibarlılıq müddəti bitmişdir!'], 422);
            }
            $data = $data[0];
            $person_info = $data['personAz'];
            $dateOfBirth = $person_info['birthdate']['date'];
            $diff = date_diff(date_create($dateOfBirth), date_create($endDate))->format('%y');
            if ($diff >= 18 || $diff <= 5) {
                return response(['message' => 'Yaşınız uyğun deyil!'], 422);
            }
            if (DB::table('personal_user_card_information')
                ->where('fin_code', $person_info['pin'])
                //->where('year', '=', date('Y'))
                ->exists()) {
                return response(['message' => 'Siz artıq müsabiqədə qeydiyyatdan keçmisiniz!'], 422);
            }

  /*          if ($request->card_old_or_new == 1) {
                $address = $person_info['iamasAddress']['fullAddress'] ?? null;
            }
            else {
                $address = $person_info['address']['address'] ?? null;
            }*/
            $address = $request->card_old_or_new == 1
                ? $person_info['iamasAddress']['fullAddress'] ?? null
                : $person_info['address']['address'] ?? null;

            $personData = [
                'name' => $person_info['name'] ?? null,
                'surname' => $person_info['surname'] ?? null,
                'patronymic' => $person_info['patronymic'] ?? null,
                'birth_date' => $person_info['birthdate']['date'] ?? null,
                'photo' => $person_info['images'][0]['imageStream'] ?? null,
                'gender' => $person_info['gender'] ?? null,
                'registration_address' => $address,
                'fin_code' => $fin_code ?? null,
                'serial_number' => $request->serial_number ?? null,
                'card_old_or_new' => $request->card_old_or_new  ?? null
            ];
            return response(['data' => $personData, 'message' => 'OK'], 200);

        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }

    public function personalParentData(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'parent_fin_code' => 'required|string|size:7',
                "parent_serial_number" => [
                    'required',
                    'numeric',
                    function (string $attribute, mixed $value, \Closure $fail) use ($request) {
                        if ($request->parents_card_old_or_new == 1 and Str::length($request->parent_serial_number) != 7) {
                            $fail("Seriya AA olduğu üçün seriya nömrəsi 7 rəqəmdən ibarət olmalıdır");
                        }
                        if ($request->parents_card_old_or_new == 2 and Str::length($request->parent_serial_number) != 8) {
                            $fail("Seriya AZE olduğu üçün seriya nömrəsi 8 rəqəmdən ibarət olmalıdır");
                        }
                    },
                ],
                'parents_card_old_or_new' => 'required|numeric|in:1,2'
            ]);
            if ($validator->fails()) {
                return response()->json(["errors" => $validator->messages()], 422);
            }
            $fin_code = $request->parent_fin_code;
            $serial_number = $request->parent_serial_number;
            if ($request->parents_card_old_or_new != 2) {
                $serial_number = "AA" . $serial_number;
            }
            $data = Http::withHeaders(['X-Bridge-Authorization' => env('ASAN_TOKEN')])
                ->get(env('ASAN_URL') . '?documentNumber=' . $serial_number . '&fin=' . $fin_code)->json()['data'];
            $date = date('Y-m-d');
            if (is_null($data)) {
                return response(['message' => 'FİN kod və ya seriya nömrəsi yanlışdır!'], 422);
            } elseif (empty($data)) {
                return response(['message' => 'Məlumat tapılmadı! Zəhmət olmasa məlumatları əl ilə daxil edin.', 'code' => 2], 404);
            } elseif ($data[0]['expiryDate']['date'] < $date) {
                return response(['message' => 'Sənədin etibarlılıq müddəti bitmişdir!'], 422);
            }
            $data = $data[0];
            $person_info = $data['personAz'];
            $dateOfBirth = $person_info['birthdate']['date'];
            $diff = date_diff(date_create($dateOfBirth), date_create($date))->format('%y');
            if ($diff < 18) {
                return response(['message' => 'Yaşınız uyğun deyil!'], 422);
            }
            $personData = [
                'parent_name' => $person_info['name'] ?? null,
                'parent_surname' => $person_info['surname'] ?? null,
                'parent_patronymic' => $person_info['patronymic'] ?? null,
                'parent_fin_code' => $fin_code ?? null,
                'parent_serial_number' => $request->parent_serial_number ?? null,
                'parents_card_old_or_new' => $request->parents_card_old_or_new  ?? null
            ];
            return response(['data' => $personData, 'message' => 'OK'], 200);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }
}
