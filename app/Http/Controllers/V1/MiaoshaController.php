<?php
/**
 * Created by PhpStorm.
 * User: lilong
 * Date: 2019/3/20
 * Time: 16:49
 */

namespace App\Http\Controllers\V1;


use Chunyang\Miaosha\Miaosha;

class MiaoshaController
{
    public function index()
    {
        return Miaosha::getGroups(5);
    }
}