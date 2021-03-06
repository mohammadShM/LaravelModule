<?php

namespace Mshm\User\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Mshm\User\Services\VerifyCodeService;

/**
 * @property mixed verify_code
 * @property mixed email
 */
class VerifyCodeRequest extends FormRequest
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
            'verify_code' => VerifyCodeService::getRule(),
        ];
    }
}
