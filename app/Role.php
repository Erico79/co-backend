<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $guarded = ['id'];

    const SYSADMIN = 'SYSADMIN';
    const GROUPADMIN = 'GRADMIN';
    const GROUPMEMBER = 'GRMEMBER';

    public function users() {
        return $this->hasMany(User::class);
    }

    public static function systemAdmin() {
        return self::where('code', self::SYSADMIN)->first();
    }

    public static function groupAdmin() {
        return self::where('code', self::GROUPADMIN)->first();
    }
}
