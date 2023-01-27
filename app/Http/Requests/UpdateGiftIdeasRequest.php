<?php

namespace App\Http\Requests;

use Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UpdateGiftIdeasRequest extends FormRequest
{

    public function rules()
    {

        return [
            'category_id' => [
                'required',
            ],
            'item_name' => [
                'required',
                'max:25'
            ],
            'item_url' => [
                'required',
                'url'
            ],
            'price'=>[
                 'required',
                 'regex:/^\d{1,13}(\.\d{1,4})?$/',
            ],
            'merchant' => [
                'required',
                'max:190'
            ],


        ];
    }
}
