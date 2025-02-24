<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CollectiveRegistrationSecondStepRequest extends FormRequest
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
            'birthDate' => 'required|date_format:Y-m-d',
			'gender' => 'required|string',
            'registration_address' => 'required',
            'live_address' => 'required',
            'card_type' => 'required',
            'fin_code' => 'required_without:birth_card',
            'birth_card' => 'required_without:fin_code|numeric',
            'serial_number' => 'required_without:birth_card|numeric',
            'card_old_or_new' => 'required_without:birth_card'
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
            'image.required' => 'Foto mütləq yüklənilməlidir!',
            'image.image' => 'Foto şəkil formatında olmalıdır!',
            'image.max' => 'Fotonun həcmi maksimum 5mb olmalıdır!',
            'name.required' => 'İştirakçının adı sahəsi boş buraxıla bilməz!',
            'name.string' => 'İştirakçının adı sahəsi tekst formatında olmalıdır!',
            'surname.required' => 'Soyadı sahəsi boş buraxıla bilməz!',
            'surname.string' => 'Soyadı sahəsi tekst formatında olmalıdır!',
            'patronymic.required' => 'Atasının adı sahəsi boş buraxıla bilməz!',
            'patronymic.string' => 'Atasının adı sahəsi tekst formatında olmalıdır!',
            'birthDate.required' => 'Doğum tarixi sahəsi boş buraxıla bilməz!',
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
