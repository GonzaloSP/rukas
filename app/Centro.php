<?php
/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  App
 * @author   Gonzalo Perilhou <perilhou@gmail.com>
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Centro extends Model
{
    protected $connection = 'mysql2';
    protected $table = 'CENTRO';
    public $timestamps = false;
}
