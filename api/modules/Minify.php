<?php

namespace V2\Modules;

use V2\Libraries\TinyHtmlMinifier;

class Minify
{
    public static function html(string $html) : string
    {
        $tinyHtml = new TinyHtmlMinifier([
            'collapse_whitespace' => true,
            'disable_comments' => true,
        ]);
        return $tinyHtml->minify($html);
    }
}
