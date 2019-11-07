<?php

namespace App;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;
class User extends Authenticatable
{
    use Notifiable,HasApiTokens;
    //
    protected $fillable = [
      'name', 'email', 'phone', 'address', 'time', 'role_id','password','provinces'
    ];

    protected $hidden = [
        'password',
    ];


    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
