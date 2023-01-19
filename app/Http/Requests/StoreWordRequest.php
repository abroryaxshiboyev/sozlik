<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreWordRequest extends FormRequest
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
            'category_id'=>'integer',
            'latin'=>'required|unique:words,latin',
            'kiril'=>'required|unique:words,kiril',
            'description_latin'=>'required',
            'description_kiril'=>'required',
            'audio'=> 'nullable|mimes:ogg|max:10000'
        ];
    }
}
