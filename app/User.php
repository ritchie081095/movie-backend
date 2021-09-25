<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\Model;
use Laravel\Lumen\Auth\Authorizable;
use Tymon\JWTAuth\Contracts\JWTSubject;

use App\UserAuthFactor;
use App\Notifications\TwoFactorCode;
use Carbon\Carbon;

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject
{
    use Authenticatable, Authorizable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'email', 'password', 'roles', 'user_status',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'roles' => 'array',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function generateTwoFactorCode(){
        $query = UserAuthFactor::create([
            "user_id" => auth()->user()->id,
            "two_factor_code" => rand(100000, 999999),
            "two_factor_expires_at" => Carbon::now()->addMinutes(10),
        ]);
        return $query;
    }

    public function resetTwoFactorCode(){
        UserAuthFactor::where('user_id', auth()->user()->id)->delete();
    }

    public function sendTwoFactorEmail($data){
        $user = auth()->user();
        // $user->notify(new TwoFactorCode($data));
    }
}
