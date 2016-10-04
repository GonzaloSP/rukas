<?php

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class LISEMP extends Eloquent
{
    protected $connection = 'mysql2';

    protected $table = 'LISEMP';
    public $timestamps = false;
}
