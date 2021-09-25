<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserAuthFactor extends Model
{
    protected $fillable = [
        "user_id",
        "two_factor_code",
        "two_factor_expires_at",
    ];
}
