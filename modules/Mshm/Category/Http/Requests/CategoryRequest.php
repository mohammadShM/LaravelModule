<?php

namespace Mshm\Category\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property mixed title
 * @property mixed slug
 * @property mixed parent_id
 */
class CategoryRequest extends FormRequest
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
            'title' => 'required|max:190',
            'slug' => 'required|max:190',
            'parent_id' => 'nullable|exists:categories,id',
        ];
    }
}
