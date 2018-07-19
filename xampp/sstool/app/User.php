<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    //protected $table = 'users';
    //primary key
    //protected $primarykey = 'user_id'; 
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */


    protected $fillable = [
        'type','name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
    

    public function posts(){
        return $this->hasMany('App\Post');
    }
}
