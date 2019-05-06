<?php

namespace V2\Email;

class Languages
{
    protected static function text(int $numeric)
    {
        global $templateType, $language;

        if ($templateType == 'account_activation') {
            if ($language == 'spanish') {
                switch ($numeric) {
                    case 1:
                        return 'Estas a solo un paso de abrir tu cuenta Dicabeg';
                    case 2:
                        return 'Dentro de la app, escribe el
                        siguiente código para activar tu cuenta.';
                    case 3:
                        return 'Le damos la bienvenida a la comunidad.';
                    case 4:
                        return '[ El equipo de Dicabeg ]';
                }
            } else if ($language == 'english') {
                switch ($numeric) {
                    case 1:
                        return '1';
                    case 2:
                        return '2';
                    case 3:
                        return '3';
                    case 4:
                        return '4';
                }
            }

        } elseif ($templateType == 'password_recovery') {
            switch ($numeric) {
                case 1:
                    return 'Ahora podras recuperar tu cuenta Dicabeg';
                case 2:
                    return 'Dentro de la app, ingresa este codigo
                    para reetablecer tu contraseña.';
                case 3:
                    return 'Su cuenta será reestablecida de inmediato.';
                case 4:
                    return '[El equipo de Dicabeg]';
            }
        }
    }
}
