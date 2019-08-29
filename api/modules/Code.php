<?php

namespace Modules\Tools;

use Libraries\Gui;
use Modules\Exceptions\CodeException;

class Code
{
    private const CODE_LENGTH = 6;

    public static function create(): string
    {
        $code = Gui::create();
        $code = preg_replace('|-|', '', $code);
        $code = strtoupper($code);
        return substr($code, 0, self::CODE_LENGTH);
    }

    public static function validate(string $input, string $saved): bool
    {
        if ($saved == 'expire') throw new CodeException('expired');
        elseif ($saved == 'used') throw new CodeException('used');
        elseif ($saved !== $input) throw new CodeException('incorrect');
        else return true;
    }
}
