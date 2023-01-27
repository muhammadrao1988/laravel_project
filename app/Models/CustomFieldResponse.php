<?php

namespace App\Models;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CustomFieldResponse extends BaseModel
{
    public static $module = "CustomFieldResponse";

    protected $fillable = [
        'id',
        'model',
        'modelId',
        'response',
    ];

    protected $casts = [
        'response' => 'array',
    ];
}
