<?php

require '../vendor/autoload.php';

use V2\Database\Querys;
use V2\Modules\Security;
use V2\Modules\Diffusion;
use V2\Libraries\PhpDotEnv;
use V2\Modules\EmailTemplate;

PhpDotEnv::load();

Diffusion::sendEmail(
  'edixonalbertto@gmail.com',
  $email = EmailTemplate::accountActivation(
    'ABC',
    'spanish'
  )
);

echo $email->html;

die;

// TODO: GET PLAYER

// $oneSignal = new OneSignal(
//     ONESIGNAL_USER_AUTH_KEY,
//     ONESIGNAL_APP_ID,
//     ONESIGNAL_REST_API_KEY
// );

// $player = new Player(
//     $oneSignal,
//     ONESIGNAL_APP_ID,
//     ONESIGNAL_REST_API_KEY
// );

// $players = $player->all();

// foreach ($players->response->players as $player) {
//     var_dump($player);
// }
// die;

// TODO: CREATE NOTIFICATION

// $os = new OneSignal;

// $os->createNotifi('a13befeb-4b2b-4178-b781-33981a9d7ec1');

// var_dump($os);

// die;


// TODO: GEOLOCALIZACION
// $continent = geoip_continent_code_by_name('186.185.50.109');

// if ($continent) {
//   echo 'Este sitio web está localizado en: ' . $continent;
// }

/*

D:\htdocs\api-dicabeg\api\index.php:28:
object(stdClass)[33]
  public 'id' => string 'a13befeb-4b2b-4178-b781-33981a9d7ec1' (length=36)
  public 'identifier' => string 'fTjNR7kC5ss:APA91bEGozSX9sYeawfl6i_94tczOpICoYDaPfdvvLDgNMEhBFuz3fqyX71nsVlk260QdglRF0LkWned2N1D8fUstmb1k-lJ__Zzsc8plqDeZQw3NRLBPzJlHmPnvNm7knv80Iepkjxr' (length=152)
  public 'session_count' => int 17
  public 'language' => string 'es' (length=2)
  public 'timezone' => int -14400
  public 'game_version' => string '1' (length=1)
  public 'device_os' => string '8.1.0' (length=5)
  public 'device_type' => int 1
  public 'device_model' => string 'moto e5 (XT1920DL)' (length=18)
  public 'ad_id' => string '36008522-4308-40bd-8289-0be91d6a4149' (length=36)
  public 'tags' =>
    object(stdClass)[20]
  public 'last_active' => int 1556934772
  public 'playtime' => int 8380
  public 'amount_spent' => float 0
  public 'created_at' => int 1556905488
  public 'invalid_identifier' => boolean false
  public 'badge_count' => int 0
  public 'sdk' => string '031005' (length=6)
  public 'test_type' => null
  public 'ip' => string '186.185.89.100' (length=14)
  public 'external_user_id' => null

 */


die;

// $result = Diffusion::sendEmail(
//     'edixonalbertto@gmail.com',
//     EmailTemplate::accountActivation('ABC123', 'spanish')
// );
// var_dump($result);
// die;

// $template = EmailTemplate::accountActivation('ABC123', 'spanish');
// echo $template->html;
// die;






// $request = preg_replace_callback(
//     $id,
//     function ($coincidence) {
//         var_dump($coincidence);
//         die;
//         $_id = $coincidence[0];
//         if (strlen($_id) == 36) {
//             Gui::validate($_id);
//             $_GET[] = $_id;
//             return 'id';
//         } else {
//             return 'nro';
//         }
//     },
//     $url
// );

// FORMA 1
// $uri = $_SERVER[' REQUEST_URI '];
// $uri2 = $uri;
// while (strlen($uri2) > 0) {
//    $position = strrpos($uri2, ' / ') + 1;
//    $id = substr($uri2, $position);
//    $uri2 = substr($uri2, 0, $position - 1);

//    if (Validations::gui($id)) $arr[] = $id;
// }
// $_GET[' id1 '] = $arr[0] ?? null;
// $_GET[' id2 '] = $arr[1] ?? null;
// $route = "/app.php/users/{$_GET[' id1 ']}/referrals/{$_GET[' id2 ']}";
// $correctRoute = ($route == $uri);
// var_dump($_GET, $route, $uri, $correctRoute);


// FORMA 2
// $exp = explode(' / ', trim($url, ' / '));
// // $route = "/app.php/{$id1}/referrals/{$id2}/";
// $num = 1;
// for ($i = 0; $i < count($exp); $i++) {
//     $id = $exp[$i];
//     if (Modules\Validations::gui($id)) {
//         $resource = strtoupper($exp[$i - 1]);
//         define(' ID_' . $resource, $id);
//     }
// }
// var_dump($exp, ID_REFERRALS);
// die;