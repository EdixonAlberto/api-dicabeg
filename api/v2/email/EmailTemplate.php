<?php

namespace V2\Email;

use V2\Modules\Time;

class EmailTemplate
{
    public const SUPPORT_EMAIL = 'dicabeg2019@gmail.com';
    private const PRIVACY_POLICY_LINK = '*';
    private static $code;

    public static function accountActivation(
        string $_code
    ) : EmailTemplate {

        self::$code = $_code;

        $html = self::generateEmail(
            '../v2/email/templates/accountActivationEmail.min.html'
        );

        $template = new EmailTemplate;
        $template->subject = '¡Bienvenid@ a Dicabeg! - Activa tu Cuenta';
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

    public static function report(
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

    private static function generateEmail(string $file) : string
    {
        $_file = fopen($file, 'r');
        $html = trim(fgets($_file), "'");

        $html = preg_replace('|CODE|', self::$code, $html);
        $html = preg_replace('|SUPPORT_EMAIL|', self::SUPPORT_EMAIL, $html);
        $html = preg_replace(
            '|PRIVACY_POLICY_LINK|',
            self::PRIVACY_POLICY_LINK,
            $html
        );
        return $html;
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
