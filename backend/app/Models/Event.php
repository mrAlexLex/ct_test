<?php

namespace App\Models;

class Event
{
    public static function dispatch($event, $data = []) {
        Logger::info("Dispatched event: $event");
    }

}
