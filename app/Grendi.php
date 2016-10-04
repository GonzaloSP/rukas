<?php

namespace App;

use Illuminate\Database\Eloquent\Model as Eloquent;

class GRENDI extends Eloquent
{
    protected $connection = 'mysql2';

    protected $table = 'GRENDI';
    public $timestamps = false;
}
