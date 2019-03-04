<?php
/**
 * Created by PhpStorm.
 * User: lilong
 * Date: 2019/3/1
 * Time: 10:59
 */

namespace App\Facades;


use Illuminate\Support\Facades\Facade;

class Proxy extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'proxy';
    }
}
