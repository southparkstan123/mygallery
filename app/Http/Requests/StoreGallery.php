<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

class StoreGallery extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'thumbnail' => 'required|image',
            'name' => 'required|unique:images|max:255',
        ];
    }

    /**
    * Get the error messages for the defined validation rules.
    *
    * @return array
    */
    public function messages()
    {
        return [
            'name.required' => 'Name of gallery is required',
            'name.unique' => 'Name of gallery is already used',
            'name.max' => 'Name of gallery must not longer the 255 characters',
            'thumbnail.required'  => 'Thumbnail of gallery is required',
            'thumbnail.mimes'  => 'Thumbnail must be JPEG',
            'thumbnail.max'  => 'Thumbnail must be less than 128KB',
        ];
    }
    
}
