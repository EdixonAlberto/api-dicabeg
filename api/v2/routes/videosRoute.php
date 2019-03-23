<?php

use V2\Modules\Route;
use V2\Controllers\VideoController;

const VIDEOS_SET = '
	video_id, name, link, provider_logo,
	question, correct, responses, total_views,
	create_date, update_date
';

try {
	Route::get('/videos', new VideoController);

} catch (\Exception $err) {

}
