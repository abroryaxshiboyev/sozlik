<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateWordRequest extends FormRequest
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
            'categories_id'=>'integer',
            'latin'=>'string',
            'kiril'=>'string',
            'description_latin'=>'string',
            'description_kiril'=>'string',
            'audio'=> 'nullable|mimes:ogg,mp3|max:10000',
            'id'=>'required',
        ];
    }
}
