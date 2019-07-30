<?php

namespace V2\Controllers;

use Exception;
use V2\Modules\Time;
use V2\Modules\User;
use V2\Modules\Format;
use Modules\Tools\Code;
use V2\Database\Querys;
use V2\Modules\Diffusion;
use Modules\Tools\Password;
use V2\Interfaces\IResource;
use V2\Modules\JsonResponse;
use V2\Modules\EmailTemplate;

class ConfigController implements IResource
{
    public static function updateEmail($req)
    {
        $body = $req->body;
        $newEmail = Format::email($body->email ?? $body->new_email ?? '');

        if (isset($body->temporal_code)) {
            $saved_temporal_code = Querys::table('accounts')
                ->select('temporal_code')
                ->where('email', User::$email)
                ->get();

            if (Code::validate($body->temporal_code, $saved_temporal_code)) {
                Querys::table('users')->update(['email' => $newEmail])
                    ->where('email', User::$email)
                    ->execute(function () {
                        throw new Exception('could not update email', 500);
                    });

                Querys::table('accounts')->update([
                    'email' => $newEmail,
                    'temporal_code' => 'used'
                ])->where('email', User::$email)
                    ->execute();
            }

            $info['email_successfull'] = Diffusion::sendEmail(
                $body->send_email,
                $newEmail,
                (new EmailTemplate)->successfull([
                    'message' => 'Has actualizado tu correo electrónico'
                ])
            );
            $info['new_email'] = $newEmail;

            JsonResponse::OK('email updated', $info);
        } else {
            $emailFound = Querys::table('users')->select('email')
                ->where('email', $newEmail)->get();

            if ($emailFound) throw new Exception(
                "email exist: {$emailFound}",
                400
            );

            // Se notifica al usuario por medio de su email actual
            $info['email_advise'] = Diffusion::sendEmail(
                $body->send_email,
                User::$email,
                (new EmailTemplate)->advise([
                    'oldEmail' => User::$email,
                    'newEmail' =>  $newEmail,
                    'date' => Time::current()->utc
                    // 'location' => 'Venezuela'
                    // TODO: conseguir datos del user para enviarlos a su email
                ])
            );

            $code = Code::create();

            Querys::table('accounts')->update([
                'last_email_sended' => 'emailUpdate',
                'temporal_code' =>  $code,
                'code_create_date' => Time::current()->utc
            ])->where('email', User::$email)->execute();

            // Se envia codigo de activacion al nuevo email
            $info['email_emailUpdate'] = Diffusion::sendEmail(
                $body->send_email,
                $newEmail,
                (new EmailTemplate)->emailUpdate(['code' =>  $code])
            );

            JsonResponse::OK('email sended',  $info);
        }
    }

    public static function updatePassword($req)
    {
        $body =  $req->body;

        if (isset($body->temporal_code)) {
            $newPass = Password::create($body->password ??  $body->new_password ?? '');

            $saved_temporal_code = Querys::table('accounts')
                ->select('temporal_code')
                ->where('email', User::$email)
                ->get();

            if (Code::validate($body->temporal_code,  $saved_temporal_code)) {
                Querys::table('users')->update(['password' =>  $newPass])
                    ->where('email', User::$email)
                    ->execute(function () {
                        throw new Exception('could not update password', 500);
                    });

                Querys::table('accounts')->update(['temporal_code' => 'used'])
                    ->where('email', User::$email)
                    ->execute();
            }

            $info['email_successfull'] = Diffusion::sendEmail(
                $body->send_email,
                User::$email,
                (new EmailTemplate)->successfull([
                    'message' => 'Has actualizado tu contraseña'
                ])
            );

            JsonResponse::OK('password updated',  $info);
        } else {
            $code = Code::create();

            Querys::table('accounts')->update([
                'last_email_sended' => 'passUpdate',
                'temporal_code' =>  $code,
                'code_create_date' => Time::current()->utc
            ])->where('email', User::$email)->execute();

            $info['email_passUpdate'] = Diffusion::sendEmail(
                $body->send_email,
                User::$email,
                (new EmailTemplate)->passUpdate(['code' =>  $code])
            );

            JsonResponse::OK('email sended',  $info);
        }
    }
}
