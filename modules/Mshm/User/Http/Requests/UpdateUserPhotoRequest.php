<?php

namespace Mshm\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserPhotoRequest extends FormRequest
{

    public function authorize()
    {
        return auth()->check() == true;
    }

    public function rules()
    {
        return [
            'userPhoto' => 'required|mimes:jpg,jpeg,png'
        ];
    }

}
