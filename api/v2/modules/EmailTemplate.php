<?php

namespace V2\Modules;

use V2\Modules\Minify;
use Jenssegers\Blade\Blade;

class EmailTemplate
{
    public const SUPPORT_EMAIL = 'dicabeg2019@gmail.com';
    public static $subject;
    public $html, $code;

    private const VIEWS_PATH = '../views/templates';
    private const CACHE_PATH = '../views/cache';
    private $arrayContentDefault;
    private $blade;

    public function __construct()
    {
        $this->blade = new Blade(self::VIEWS_PATH, self::CACHE_PATH);

        $this->arrayContentDefault = [
            'style' => self::styleLoader(),
            'support' => self::SUPPORT_EMAIL,
        ];
    }

    public function __call(string $templateType, array $arguments): EmailTemplate
    {
        if (empty($arguments[0])) $data = null;
        else [$data] = $arguments;

        $flatHtml = $this->blade->render(
            $templateType,
            array_merge($this->arrayContentDefault, [
                'data' => (object) $data
            ])
        );
        $this->html = Minify::html($flatHtml);
        return $this;
    }

    public static function styleLoader(): string
    {
        $resource = fopen($file = 'css/emailStyle.css', 'r');
        $css = trim(fread($resource, filesize($file)), "'");
        fclose($resource);
        return $css;
    }
}
