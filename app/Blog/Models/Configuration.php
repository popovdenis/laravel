<?php
namespace App\Blog\Models;

use Illuminate\Database\Eloquent\Model;

/**
 *
 */
class Configuration extends Model
{
    protected $primaryKey = 'key';

    public $fillable = [
        'key',
        'value'
    ];

    public static function get($key){
        $obj = Configuration::where('key', $key)->first();
        if ($obj){
            return $obj->value;
        }
        else{
            return null;
        }
    }

    public static function set($key, $value){
        $config = new Configuration();
        $config->key = $key;
        $config->value = $value;
        $config->save();
    }
}
