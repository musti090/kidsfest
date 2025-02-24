<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CollectiveRegistrationFirstStepRequest extends FormRequest
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
            'collective_name' => 'required|string',
            'collective_created_date' => 'required|numeric',
            'collective_mn_region_id' => 'required|numeric',
            'collective_nomination_id' => 'required|numeric'
        ];
    }
    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
            'collective_name.required' => 'Kollektivin adı sahəsi boş buraxıla bilməz!',
            'collective_name.string' => 'Kollektivin adı sahəsi tekst formatında olmalıdır!',
            'collective_created_date.required' => 'Yarandığı il sahəsi boş buraxıla bilməz!',
            'collective_created_date.numeric' => 'Yarandığı il sahəsi rəqəmlərdən ibarət olmalıdır!',
        ];
    }
}
