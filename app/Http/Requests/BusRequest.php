<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BusRequest extends FormRequest
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
            'firstname' => 'required|string|min:3|max:100',
            'secondname' => 'required|string|min:3|max:100',
            'phone_no' => 'required|' . mobile_regex() . '|unique:users,phone_no',
            'password' => password_rules(true),
            'no_bus' => 'required',
            'role'=>'required',

        ];
    }
}
