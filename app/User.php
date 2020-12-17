<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    // Don't add create and update timestamps in database.
    public $timestamps  = false;

    protected $table = 'profile';

    protected $fillable = [
        'name', 'email', 'username', 'password', 'image'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

     public function getprojects() {
      return $this->hasMany('App\Project');
    }
}
