<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chofer extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'CHOFER';
    public $timestamps = false;
}
