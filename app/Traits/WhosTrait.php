<?php

namespace App\Traits;

use App\Observers\WhosObserver;

trait WhosTrait
{


    public static function bootWhosTrait()
    {
        static::observe(new WhosObserver);
    }
}
