<?php

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Emptte extends Eloquent
{
    protected $connection = 'mysql2';

    protected $table = 'EMPTTE';
    public $timestamps = false;
}
