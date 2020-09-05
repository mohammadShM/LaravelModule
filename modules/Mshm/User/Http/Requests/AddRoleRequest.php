<?php

namespace Mshm\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property mixed role
 */
class AddRoleRequest extends FormRequest
{

    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'role' => ['required' , 'exists:roles,name'],
        ];
    }

}
