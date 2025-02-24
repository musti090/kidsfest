<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CollectiveRegistrationThirdStepRequest extends FormRequest
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
            'parent_name' => 'required|string',
            'parent_surname' => 'required|string',
            'parent_patronymic' => 'required|string',
            'parent_fin_code' => 'required',
            'parent_serial_number' => 'required|numeric',
            'parents_card_old_or_new' => 'required',
            'phone_number' => 'required|numeric|digits:10'
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
            'parent_name.required' => 'Ad sahəsi boş buraxıla bilməz!',
            'parent_name.string' => 'Ad sahəsi tekst formatında olmalıdır!',
            'parent_surname.required' => 'Soyad sahəsi boş buraxıla bilməz!',
            'parent_surname.string' => 'Soyad sahəsi tekst formatında olmalıdır!',
            'parent_patronymic.required' => 'Ata adı sahəsi boş buraxıla bilməz!',
            'parent_patronymic.string' => 'Ata adı sahəsi tekst formatında olmalıdır!',
            'parent_fin_code.required' => 'Şəxsiyyət vəsiqəsinin Fin kodu sahəsi  boş buraxıla bilməz!',
            'parent_serial_number.required' => 'Şəxsiyyət vəsiqəsinin seriya nömrəsi sahəsi  boş buraxıla bilməz!',
            'parent_serial_number.numeric' => 'Şəxsiyyət vəsiqəsinin seriya nömrəsi rəqəmlərdən ibarət olmalıdır!',
            'phone_number.required' => 'Əlaqə nömrəsi sahəsi boş buraxıla bilməz!',
            'phone_number.numeric' => 'Əlaqə nömrəsi rəqəmlərdən ibarət olmalıdır!',
            'phone_number.digits' => 'Əlaqə nömrəsi 10 rəqəmdən ibarət olmalıdır!',
        ];
    }
}
