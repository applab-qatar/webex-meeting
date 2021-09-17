<?php


namespace Applab\WebexMeeting;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;

class Authentication extends GClient
{
    //authenticate the app api user for all requests
    public function login()
    {
        try{
            if(config('applab-webex.client-id')!='' && config('applab-webex.client-secret')!='') {
                if(Cache::has('webex-access-refresh-token') || !empty(Cache::get('webex-access-refresh-token')) || !empty(config('applab-webex.refresh-token'))) {
                    $body = [
                        'grant_type' => 'refresh_token',
                        'client_id' => config('applab-webex.client-id'),
                        'client_secret' => config('applab-webex.client-secret'),
                        'refresh_token' => Cache::get('webex-access-refresh-token')??config('applab-webex.refresh-token')
                    ];
                }else {
                    $body = [
                        'grant_type' => 'authorization_code',
                        'client_id' => config('applab-webex.client-id'),
                        'client_secret' => config('applab-webex.client-secret'),
                        'code' => config('applab-webex.authorized-code'),
                        'redirect_uri' => config('applab-webex.redirect-uri')];
                }
                $response = $this->client->request('POST', 'access_token',[
                    'headers' => [
                        'Content-Type'=>'application/x-www-form-urlencoded',
                    ],
                    'form_params' => $body
                ]);
                if($response->getStatusCode()==200){
                    $response=json_decode($response->getBody());
                    if($response->access_token){
                        Cache::put('webex-access-token',$response->access_token,$response->expires_in);
                        Cache::put('webex-access-refresh-token',$response->refresh_token,$response->refresh_token_expires_in);
                        return true;
                    }
                }
                throw new \Exception("Access Token generation failed");
            }else{
                throw new \Exception('Invalid input!, Ensure configuration values are correct');
            }
        }catch(GuzzleException $e){
            \Log::critical("WEbEXAuthentication Failure ".$e->getMessage());
            throw $e;
        }
    }
}
