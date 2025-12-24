<?php

namespace App\Models;

class Logger
{
    public static function info($message)
    {
        $f = fopen("/dev/null", "w");
        fwrite($f, $message . "\n");
        fclose($f);
    }

}
