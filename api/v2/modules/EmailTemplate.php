<?php

namespace V2\Modules;

use V2\Modules\Time;
use Jenssegers\Blade\Blade;

class EmailTemplate
{
    public const SUPPORT_EMAIL = 'dicabeg2019@gmail.com';
    private const PRIVACY_POLICY_LINK =
        'https://edixonalberto.github.io/doc-dicabeg/menu/policy.html';
    private const VIEWS_PATH = 'views/templates';
    private const CACHE_PATH = 'views/cache';

    public static function accountActivation(
        string $code
    ) : EmailTemplate {

        $blade = new Blade(self::VIEWS_PATH, self::CACHE_PATH);

        $html = $blade->render('accountActivation', [
            'code' => $code,
            'support' => self::SUPPORT_EMAIL,
            'policy' => self::PRIVACY_POLICY_LINK
        ]);

        $template = new EmailTemplate;
        $template->html = $html;
        return $template;
    }

    public static function passwordRecovery(
        string $_code
    ) : EmailTemplate {

        self::$code = $_code;

        $html = self::generateEmail(
            '../v2/email/templates/recoveryPasswordEmail.min.html'
        );

        $template = new EmailTemplate;
        $template->subject = 'Recuperación de Cuenta';
        $template->html = $html;
        return $template;
    }

    public static function userReport(
        string $id,
        array $arrayData
    ) : EmailTemplate {

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

    public static function emailUpdate(
        string $code
    ) : EmailTemplate {

        $blade = new Blade(self::VIEWS_PATH, self::CACHE_PATH);

        $html = $blade->render('emailUpdate', [
            'code' => $code,
            'support' => self::SUPPORT_EMAIL,
            'policy' => self::PRIVACY_POLICY_LINK
        ]);

        $template = new EmailTemplate;
        $template->html = $html;
        return $template;
    }

    // private static function generateEmail(string $file) : string
    // {
    //     $_file = fopen($file, 'r');
    //     $html = trim(fgets($_file), "'");

    //     // TODO: colocar el codigo en el metodo que sea requerido.
    //     $html = preg_replace('|CODE|', self::$code, $html);
    //     $html = preg_replace('|SUPPORT_EMAIL|', self::SUPPORT_EMAIL, $html);
    //     $html = preg_replace(
    //         '|PRIVACY_POLICY_LINK|',
    //         self::PRIVACY_POLICY_LINK,
    //         $html
    //     );
    //     return $html;
    // }

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
