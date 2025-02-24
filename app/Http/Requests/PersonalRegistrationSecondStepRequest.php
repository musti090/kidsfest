<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PersonalRegistrationSecondStepRequest extends FormRequest
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
            'mn_region_id' => 'required|numeric',
            'school_type_id' => 'required|numeric',
            'school_id' => 'required|numeric',
            'nomination_id' => 'required|numeric',
            'art_type' => 'required|numeric',
            'art_school_id' => 'nullable|numeric',
            'art_education' => 'nullable|string'
        ];
    }

}
