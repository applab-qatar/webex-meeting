<?php

use Applab\WebexMeeting\WebexMeeting;
use Carbon\Carbon;

Route::group(['middleware' => ['webex'],'prefix'=>'webex'], function () {//, 'auth'
//    Route::get('authorized', function(){
//        Config::set('applab-webex.authorized-code', '');
//    });
    Route::group(['prefix'=>'webhooks'], function () {
        Route::post('meeting', function(){

        });
    });
});
