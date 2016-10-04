<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bdit extends Model
{
    protected $table = 'BDIT';
    public $timestamps = false;
    
    public static function convertDate( $date1 ){
        //echo $date1 . "<br>";
        $arr_date   = explode("/", $date1);
        $date2      = "";
        
        if( !empty($arr_date) ){
            if(isset($arr_date[2])){
                $date2  = $arr_date[2] . "-" . $arr_date[0] . "-" . $arr_date[1];
            }
        }
        return $date2;
    }
}
