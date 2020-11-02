<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'firstname' => 'required|string|min:3',
            'secondname' => 'required|string|min:3',
            'phone_no' => 'required|' . mobile_regex() . '|unique:users,phone_no',
            'city_id' => 'required',
            'password' => password_rules(true),
            'fcm_token' => 'required',
        ];


    }
    public function messages()
    {
        return [
            'firstname.required' => 'تنبيه!عليك ادخال جميع الحقول الفارغة',
            'firstname.min' => 'عليك ادخال الاسم أكثرمن تلات حروف ',
            'secondname.required' => 'تنبيه!عليك ادخال جميع الحقول الفارغة',
            'secondname.min' => 'عليك ادخال الاسم أكثرمن تلات حروف ',
            'phone_no.required' => 'تنبيه!عليك ادخال جميع الحقول الفارغة',
            'phone_no.unique' => 'رقم الهاتف مستخدم سابقاَ',
            'city_id.required' => 'تنبيه!عليك ادخال جميع الحقول الفارغة',
            'fcm_token.required' => 'تنبيه!عليك ادخال جميع الحقول الفارغة',

        ];
    }
}
