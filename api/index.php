<?php

require '../vendor/autoload.php';

use V2\Modules\Middleware;
// use V2\Libraries\PhpDotEnv;

// PhpDotEnv::load();

$response = (object)[
    'player_id' => '1-23',
    'names' => 'Edi',
    'lastnames' => 'Piña',
    'age' => 27,
    'password' => 'ABC'
];

$resp = Middleware::output($response);
var_dump($resp);

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