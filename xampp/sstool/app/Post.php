<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    //table name
    protected $table = 'posts';
    //primary key
    protected $primarykey = 'id';
    //timestamps
    public $timestamps = true;

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function ips(){
        return $this->hasMany('App\Ip');
    }
}
