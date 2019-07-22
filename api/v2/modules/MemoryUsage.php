<?php

namespace V2\Modules;

class MemoryUsage
{
    const UNIT = ['b', 'kb', 'mb', 'gb', 'tb', 'pb'];

    public static function determine()
    {
        $size = memory_get_usage(true);
        return  @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . self::UNIT[$i];
    }
}
