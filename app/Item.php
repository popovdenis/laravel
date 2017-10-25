<?php
/**
 * User: Denis Popov
 * Date: 15.10.2017
 * Time: 13:43
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    public $fillable = ['title','description'];
}
