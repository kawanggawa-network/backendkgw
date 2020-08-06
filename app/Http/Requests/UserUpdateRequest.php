<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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
            'name'      => 'max:200',
            'email'     => 'email|unique:users,email,' . request()->user()->id . '|max:200',
            'photo'     => 'mimes:jpeg,png,bmp|max:512',
            'password'  => 'confirmed|max:200',
            'phone_number' => 'numeric'
        ];
    }
}
