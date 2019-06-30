<?php

namespace V2\Modules;

use V2\Modules\Time;
use V2\Modules\Minify;
use Jenssegers\Blade\Blade;

class EmailTemplate
{
    public const SUPPORT_EMAIL = 'dicabeg2019@gmail.com';
    private const VIEWS_PATH = 'views/templates';
    private const CACHE_PATH = 'views/cache';

    private $arrayContent;
    private $blade;

    public function __construct()
    {
        $this->blade = new Blade(self::VIEWS_PATH, self::CACHE_PATH);
        $this->arrayContent = [
            'style' => self::styleLoader(),
            'support' => self::SUPPORT_EMAIL,
        ];
    }

    public function __call($templateType, $code)
    {
        $this->html = $this->blade->render(
            $templateType,
            array_merge($this->arrayContent, ['code' => $code[0]])
        );
        $this->subject = SUBJECT;
        return $this;
    }

    public function userReport(string $id, array $arrayData) : self
    {
        $html = self::generateEmail(
            '../v2/email/templates/reportEmail.min.html'
        );

        $month = strftime('%m', strtotime($arrayData[0]->create_date));

        $html = preg_replace('|MONTH|', $month, $html);
        $html = preg_replace('|ID|', $id, $html);
        $html = self::generateReport($arrayData, $html);

        $template = new EmailTemplate;
        $template->subject = 'Reporte de Transferencia de Usuario';
        $template->html = $html;
        return $template;
    }

    public static function styleLoader() : string
    {
        $resource = fopen($file = 'public/css/style.css', 'r');
        $css = trim(fread($resource, filesize($file)), "'");
        fclose($resource);
        return $css;
    }

    private static function generateReport(
        array $arrayData,
        string $html
    ) : string {

        $total_amount = $total_gain = 0;
        foreach ($arrayData as $key => $data) {
            $amount = $data->amount;
            $total_amount += $amount;

            $gain = $data->gain;
            $total_gain += $gain;

            $last_date = $data->create_date;
        }

        $html = preg_replace('|TOTAL_AMOUNT|', $total_amount, $html);
        $html = preg_replace('|TOTAL_GAIN|', $total_gain, $html);
        $html = preg_replace('|LAST_DATE|', $last_date, $html);

        return $html;
    }
}
