<?php

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Vended extends Eloquent
{
    protected $connection = 'mysql2';
    protected $table = 'VENDED';
    public $timestamps = false;
}
