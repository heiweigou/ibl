<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ip extends Model
{
    //table name
    protected $table = 'ips';

    //primary key
    protected $primarykey = 'id';

    //range in original format
    public $range;

    //type of IP range - scan during business hour? 'y' - scan anytime 'n' - scan after 7pm

    public $type;

    //(Long Number Format IP)the lowest IP within the range *low and high are the same in the case of single ip 
    public $low;

    //(Long Number Format IP)the highest IP within the range
    public $high;

    public function describe(){
        return '['+long2ip($this->low)+','+long2ip($this->high)+']';
    }

    public function post(){
        return $this->belongsTo('App\Post');
    }
}
