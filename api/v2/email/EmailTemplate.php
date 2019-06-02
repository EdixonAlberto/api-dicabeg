<?php

namespace V2\Email;

class EmailTemplate
{
    public const SUPPORT_EMAIL = 'dicabeg2019@gmail.com';
    private const PRIVACY_POLICY_LINK = '*';
    private static $code;

    public static function accountActivation(
        string $_code,
        string $_language
    ) {
        global $language;

        self::$code = $_code;
        $language = $_language;

        $html = self::generateEmail(
            '../v2/email/templates/accountActivationEmail.min.html'
        );

        $template = new EmailTemplate;
        $template->subject = '¡Bienvenid@ a Dicabeg! - Activa tu Cuenta';
        $template->html = $html;
        return $template;
    }

    public static function passwordRecovery(
        string $_code,
        string $_language
    ) {
        global $_language;

        self::$code = $_code;
        $language = $_language;

        $html = self::generateEmail(
            '../v2/email/templates/recoveryPasswordEmail.min.html'
        );

        $template = new EmailTemplate;
        $template->subject = 'Recuperación de Cuenta';
        $template->html = $html;
        return $template;
    }

    private static function generateEmail($file)
    {
        $_file = fopen($file, 'r');
        $html = trim(fgets($_file), "'");

        // $html = preg_replace('|text1|', self::text(1), $html);
        // $html = preg_replace('|text2|', self::text(2), $html);
        // $html = preg_replace('|text3|', self::text(3), $html);
        // $html = preg_replace('|text4|', self::text(4), $html);

        $html = preg_replace('|CODE|', self::$code, $html);
        $html = preg_replace('|SUPPORT_EMAIL|', self::SUPPORT_EMAIL, $html);
        $html = preg_replace(
            '|PRIVACY_POLICY_LINK|',
            self::PRIVACY_POLICY_LINK,
            $html
        );
        return $html;
    }
}
