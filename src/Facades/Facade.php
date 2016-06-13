<?php
/**
 * Created by PhpStorm.
 * User: Brett
 * Date: 2016/6/13
 * Time: 23:17
 */

namespace Seek\NLPTool\Facades;


class Facade extends \Illuminate\Support\Facades\Facade
{
    protected static function getFacadeAccessor()
    {
        return 'seek.NLPTool';
    }
}