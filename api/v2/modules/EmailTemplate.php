<?php

namespace V2\Modules;

use V2\Modules\Minify;
use Jenssegers\Blade\Blade;

class EmailTemplate
{
    public const SUPPORT_EMAIL = 'dicabeg2019@gmail.com';
    public static $subject;
    public $html, $code;

    private $blade;
    private const VIEWS_PATH = '../views/templates';
    private const CACHE_PATH = '../views/cache';
    private const DATASERVER_URL = [
        'prod' => "https://db-dicabeg.herokuapp.com",
        'dev' => "http://db-dicabeg.test:3000"
    ];

    public function __construct()
    {
        $this->contentDefaultLoader();
        $this->blade = new Blade(self::VIEWS_PATH, self::CACHE_PATH);
    }

    public function __call(string $templateType, array $arguments): EmailTemplate
    {
        if (empty($arguments)) $data = null;
        else [$data] = $arguments;

        $flatHtml = $this->blade->render(
            $templateType,
            [
                'style' => $this->styleLoader(),
                'data' => (object) $data
            ]
        );
        $this->html = Minify::html($flatHtml);
        $this->code = $data['code'] ?? null;
        return $this;
    }

    private function styleLoader(): string
    {
        $resource = fopen($file = 'css/emailStyle.css', 'r');
        $css = trim(fread($resource, filesize($file)), "'");
        fclose($resource);
        return $css;
    }

    private function contentDefaultLoader(): void
    {
        define('SUPPORT_EMAIL', self::SUPPORT_EMAIL);
        define('DATASERVER_URL', (APP_ENV == 'dev') ?
            self::DATASERVER_URL['dev'] : self::DATASERVER_URL['prod']);
    }
}
