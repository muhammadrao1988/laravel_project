<?php

namespace App\Models;

class UserRole extends BaseModel
{
    protected $fillable = [
        'id', 'user_id', 'role_id'
    ];
}
