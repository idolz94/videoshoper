<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    //
    protected $fillable = [
      'name', 'email', 'phone', 'address', 'time', 'role_id','password'
    ];

    protected $hidden = [
        'password'
    ];


    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
