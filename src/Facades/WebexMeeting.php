<?php
namespace Applab\WebexMeeting\Facades;

use Illuminate\Support\Facades\Facade;

class WebexMeeting extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'webex-meeting';
    }
}