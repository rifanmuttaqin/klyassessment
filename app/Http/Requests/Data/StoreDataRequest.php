<?php

namespace App\Http\Requests\Data;

use Illuminate\Foundation\Http\FormRequest;

class StoreDataRequest extends FormRequest
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
            'name'              => 'required|min:2',
            'email'             => 'required|email',
            'date_of_birth'     => 'required|date',
            'phone_number'      => 'required|string',
            'profile_picture'   => 'string',
            'gender'            => 'required|integer',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [

            'name.required' => 'Name tidak boleh dikosongkan',
            'name.min'      => 'Name setidaknya 2 karakter',

            'email.required'    => 'Email tidak boleh dikosongkan',
            'email.email'       => 'Format email tidak disetujui',

            'gender.integer'    => 'Format gender tidak disetujui',

            'date_of_birth.date'    => 'Format harus berupa Tanggal',
        ];
    }
}
