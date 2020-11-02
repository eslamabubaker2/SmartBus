<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterSchoolRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'beginning_of_time' => 'required|date_format:H:i',
            'End_of_time' => 'required|date_format:H:i',
        ];

    }
    public function messages()
    {
        return [
            'beginning_of_time.required' => 'تنبيه!عليك ادخال جميع الحقول الفارغة',
            'End_of_time.required' => 'تنبيه!عليك ادخال جميع الحقول الفارغة',
            'beginning_of_time.date_format' => 'تنبيه!عليك ادخال   الوقت على النمط التالى 00:00',
            'End_of_time.date_format' => 'تنبيه!عليك ادخال   الوقت على النمط التالى 00:00',

        ];
    }
}
