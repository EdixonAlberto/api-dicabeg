<?php

namespace V2\Modules;

use V2\Libraries\TinyHtmlMinifier;

class Minify
{
    public static function html(string $html) : string
    {
        $tinyHtml = new TinyHtmlMinifier([
            'collapse_whitespace' => true,
            'disable_comments' => false,
        ]);
        return $tinyHtml->minify($html);
    }
}
