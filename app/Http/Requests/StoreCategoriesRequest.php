<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StoreCategoriesRequest extends FormRequest
{
    public function rules()
    {
        return [
            'title' => [
                'required',
            ],

            'image' => 'required|mimes:jpeg,jpg,png|max:2048|dimensions:min_width=1920,min_height=600'
        ];
    }
    public function messages()
    {
        return [
            'image.dimensions' => 'The image must be at least 1920 x 600 pixels!',
        ];
    }
}
