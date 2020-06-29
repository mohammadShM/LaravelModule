<?php

namespace Mshm\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Mshm\User\Rules\ValidPassword;
use Mshm\User\Services\VerifyCodeService;

/**
 * @property mixed verify_code
 * @property mixed email
 * @property mixed password
 */
class ChangePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check() == true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'password' => ['required', new ValidPassword(), 'confirmed'],
        ];
    }
}
