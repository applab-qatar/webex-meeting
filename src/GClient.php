<?php


namespace Applab\WebexMeeting;


use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;

class GClient extends Client
{
    protected $client;
    public function __construct()
    {
        $this->client=new Client([
            'base_uri'=>"https://webexapis.com/v1/"
        ]);
        return $this->client;
    }
}
