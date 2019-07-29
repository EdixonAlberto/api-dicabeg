<?php

namespace V2\Controllers;

use Exception;
use V2\Modules\Time;
use V2\Modules\User;
use V2\Modules\Format;
use V2\Database\Querys;
use V2\Modules\Security;
use V2\Modules\Diffusion;
use V2\Interfaces\IResource;
use V2\Modules\JsonResponse;
use V2\Modules\EmailTemplate;

class ConfigController implements IResource
{
    public static function updateEmail($req)
    {
        $body = $req->body;
        $newEmail = Format::email($body->new_email ?? null);

        if (isset($body->temporal_code)) {
            Querys::table('accounts')->update(['temporal_code' => 'used'])
                ->where('temporal_code', $body->temporal_code)
                ->execute(function () {
                    throw new Exception('code incorrect', 400);
                });

            Querys::table('users')->update(['email' => $newEmail])
                ->where('user_id', User::$id)
                ->execute(function () {
                    throw new Exception('could not update email', 500);
                });

            $emailStatus = Diffusion::sendEmail(
                $body->send_email,
                $newEmail,
                (new EmailTemplate)->successfull([
                    'message' => 'Has actualizado tu correo electrónico'
                ])
            );

            JsonResponse::OK('email updated', $emailStatus);
        } else {
            // Se notifica al usuario por medio de su email actual
            $info['email_advise'] = Diffusion::sendEmail(
                $body->send_email,
                User::$email,
                function ($send) use ($newEmail) {
                    if ($send) return (new EmailTemplate)->advise([
                        'oldMail' => User::$email,
                        'newMail' => $newEmail,
                        'date' => Time::current()->utc,
                        'location' => Time::$timeZone
                    ]);
                }
            );

            $code = Security::generateCode(6);

            Querys::table('accounts')->update([
                'last_email_sended' => 'emailUpdate',
                'temporal_code' => $code,
                'code_create_date' => Time::current()->utc
            ])->where('user_id', User::$id)->execute();

            // Se envia codigo de activacion al nuevo email
            $info['email_emailUpdate'] = Diffusion::sendEmail(
                $body->send_email,
                $newEmail,
                (new EmailTemplate)->emailUpdate(['code' => $code])
            );

            JsonResponse::OK('email sended', $emailStatus);
        }
    }

    public static function updatePassword($req)
    {
        $body = $req->body;

        if (isset($body->temporal_code)) {
            $newPass = Security::generatePass($body->password ?? null);

            Querys::table('accounts')->update(['temporal_code' => 'used'])
                ->where('temporal_code', $body->temporal_code)
                ->execute(function () {
                    throw new Exception('code incorrect', 400);
                });

            Querys::table('users')->update(['password' => $newPass])
                ->where('user_id', User::$id)
                ->execute(function () {
                    throw new Exception('could not update password', 500);
                });

            $emailStatus = Diffusion::sendEmail(
                $body->send_email,
                User::$email,
                (new EmailTemplate)->successfull([
                    'message' => 'Has actualizado tu contraseña'
                ])
            );

            JsonResponse::OK('password updated', $emailStatus);
        } else {
            $code = Security::generateCode(6);

            Querys::table('accounts')->update([
                'last_email_sended' => 'passUpdate',
                'temporal_code' => $code,
                'code_create_date' => Time::current()->utc
            ])->where('user_id', User::$id)->execute();

            $info['email_passUpdate'] = Diffusion::sendEmail(
                $body->send_email,
                User::$email,
                (new EmailTemplate)->passUpdate(['code' => $code])
            );

            JsonResponse::OK('email sended', $emailStatus);
        }
    }
}
