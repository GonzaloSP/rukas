<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Soldto extends Model
{
    protected $table = 'SOLDTO';
    protected $connection = 'mysql2';
    public $timestamps = false;
}
