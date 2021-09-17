<?php

use Applab\WebexMeeting\WebexMeeting;
use Carbon\Carbon;

Route::group(['middleware' => ['web','auth'],'prefix'=>'test'], function () {//, 'auth'
    Route::get('create-meeting', function () {
        $applabWebex=new WebexMeeting();
        $meeting= [
            "title"=> "Qatar Event",
            "agenda"=> "Qatar Event Sample Agenda",
            "password"=> "A@ssword123",
            "start"=> Carbon::tomorrow()->format('Y-m-d H:i:s'),
            "end"=> Carbon::tomorrow()->addHour()->format('Y-m-d H:i:s'),
            "timezone"=> "UTC",
            "enabledAutoRecordMeeting"=> false,
            "allowAnyUserToBeCoHost"=> false
        ];

        $response= $applabWebex->createMeeting($meeting);
        echo "<pre>";print_r($response);
    });
});
