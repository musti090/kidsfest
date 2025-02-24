<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class PersonalRegistrationFirstStepRequest extends FormRequest
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
            'photo' => 'required|image|max:5120',
            'name' => 'required|string',
            'surname' => 'required|string',
            'patronymic' => 'required|string',
			'gender' => 'required|string',
            'birth_date' => 'required|date_format:Y-m-d',
            'registration_address' => 'required',
            'live_address' => 'required'
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
            'photo.required' => 'Foto mütləq yüklənilməlidir!',
            'photo.image' => 'Foto şəkil formatında olmalıdır!',
            'photo.max' => 'Fotonun həcmi maksimum 5mb olmalıdır!',
            'name.required' => 'İştirakçının adı sahəsi boş buraxıla bilməz!',
            'name.string' => 'İştirakçının adı sahəsi tekst formatında olmalıdır!',
            'surname.required' => 'Soyadı sahəsi boş buraxıla bilməz!',
            'surname.string' => 'Soyadı sahəsi tekst formatında olmalıdır!',
            'patronymic.required' => 'Atasının adı sahəsi boş buraxıla bilməz!',
            'patronymic.string' => 'Atasının adı sahəsi tekst formatında olmalıdır!',
            'birth_date.required' => 'Doğum tarixi sahəsi boş buraxıla bilməz!',
            'registration_address.required' => 'Qeydiyyat ünvanı sahəsi boş buraxıla bilməz!',
            'live_address.required' => 'Faktiki yaşayış ünvanı sahəsi boş buraxıla bilməz!',
            'fin_code.required' => 'Fin kod sahəsi ( Doğum haqqında şəhadətnamə seçilməyibsə ) boş buraxıla bilməz!',
            'serial_number.required' => 'Şəxsiyyət vəsiqəsinin seriya nömrəsi sahəsi ( Doğum haqqında şəhadətnamə seçilməyibsə ) boş buraxıla bilməz!',
            'serial_number.numeric' => 'Şəxsiyyət vəsiqəsinin seriya nömrəsi rəqəmlərdən ibarət olmalıdır!',
            'birth_card.required' => 'Şəxsiyyət vəsiqəsinin seriya nömrəsi sahəsi ( Fin kod seçilməyibsə ) boş buraxıla bilməz!',
            'birth_card.numeric' => 'Doğum haqqında şəhadətnamə rəqəmlərdən ibarət olmalıdır!',
        ];
    }
}
