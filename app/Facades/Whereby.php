<?php
namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Whereby extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \A4Anthony\WherebyLaravel\WherebyLaravel::class;
    }
}
