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
            'categories_id'=>'required|exists:categories,id',
            'latin'=>'required',
            'kiril'=>'required|unique:words,kiril',
            'description_latin'=>'required',
            'description_kiril'=>'required',
            'audio'=> 'nullable|mimes:ogg,mp3,wav|max:10000'
        ];
    }
}
