<?php

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Emrtte extends Eloquent
{
    protected $connection = 'mysql2';
    protected $table = 'EMRTTE';
    public $timestamps = false;
}
