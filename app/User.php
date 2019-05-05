<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'first_name',
      'last_name',
      'email',
      'mobile_phone',
      'password',
      'group_id',
      'role_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
      'password', 'remember_token',
    ];

    public function group() {
      return $this->belongsTo(Group::class);
    }

    public function role() {
      return $this->belongsTo(Role::class);
    }

    public function otps() {
      return $this->hasMany(Otp::class);
    }

    public static function emailExists($email) {
      return self::where('email', $email)->first();
    }

    public static function mobileNoExists($phone_no) {
      return self::where('mobile_phone', $phone_no)->first();
    }
}
