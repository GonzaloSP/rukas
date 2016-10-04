<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  App
 * @author   Gonzalo Perilhou <perilhou@gmail.com>
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Incote extends Model
{
    protected $table = 'INCOTE';
    protected $connection = 'mysql2';
    public $timestamps = false;
}
