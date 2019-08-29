<?php

namespace V2\Modules;

use Libraries\Gui;

class Security
{
    public static function generateID()
    {
        $code = Gui::create();
        return trim($code, '{}');
    }
}
