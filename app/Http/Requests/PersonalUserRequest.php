<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PersonalUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'image' => 'required|image|max:5120',
            'name' => 'required|string',
            'surname' => 'required|string',
            'patronymic' => 'required|string',
            'gender' => 'required|string',
            'birthDate' => 'required|date_format:Y-m-d',
            'registration_address' => 'required',
            'live_address' => 'required',
            'card_type' => 'required|numeric',
            'fin_code' => 'required_without:birth_card|unique:personal_user_card_information',
            'birth_card' => 'required_without:fin_code|numeric|unique:personal_user_card_information',
            'serial_number' => 'required_without:birth_card|numeric',
            'card_old_or_new' => 'required_without:birth_card|numeric',
            'mn_region_id' => 'required|numeric',
            'school_type_id' => 'required|numeric',
            'school_id' => 'required|numeric',
            'nomination_id' => 'required|numeric',
            'art_type' => 'required|numeric',
            'art_school_id' => 'nullable|numeric',
            'art_education' => 'nullable|string',
            'parent_name' => 'required|string',
            'parent_surname' => 'required|string',
            'parent_patronymic' => 'required|string',
            'parent_fin_code' => 'required',
            'parent_serial_number' => 'required|numeric',
            'parents_card_old_or_new' => 'required|numeric',
            'phone_number' => 'required|numeric|digits:10',
            'email' => 'nullable|email'
        ];
    }
}
