<?php


namespace Applab\WebexMeeting;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;

class Authentication extends GClient
{
//  REQUEST https://webexapis.com/v1/authorize?client_id=C67602ad0cf44d6738ca985d308670484ee5eac18d4d99cd67a6a7ad3ae960f10&response_type=code&redirect_uri=https%3A%2F%2Fipaq.applab.qa&scope=meeting%3Aadmin_preferences_write%20meeting%3Arecordings_read%20meeting%3Aadmin_preferences_read%20meeting%3Aparticipants_read%20meeting%3Aadmin_participants_read%20meeting%3Apreferences_write%20meeting%3Aadmin_recordings_read%20meeting%3Atranscripts_read%20spark%3Amessages_read%20meeting%3Aschedules_write%20spark%3Amemberships_read%20meeting%3Acontrols_read%20spark%3Amessages_write%20spark-admin%3Abroadworks_enterprises_write%20meeting%3Aadmin_schedule_read%20spark-compliance%3Ameetings_write%20meeting%3Aadmin_schedule_write%20meeting%3Aschedules_read%20spark%3Amemberships_write%20meeting%3Arecordings_write%20meeting%3Apreferences_read%20spark%3Akms%20meeting%3Acontrols_write%20meeting%3Aadmin_recordings_write%20meeting%3Aparticipants_write%20meeting%3Aadmin_transcripts_read&state=set_state_here
//RESPONSE https://ipaq.applab.qa/webex/authorized?code=<----here--->&state=set_state_here

    //authenticate the app api user for all requests
    public function login()
    {
        try{
            if(config('applab-webex.client-id')!='' && config('applab-webex.client-secret')!='') {
                //if(Cache::has('webex-access-refresh-token') || !empty(Cache::get('webex-access-refresh-token'))) {
                    $body = [
                        'grant_type' => 'refresh_token',
                        'client_id' => config('applab-webex.client-id'),
                        'client_secret' => config('applab-webex.client-secret'),
                        'refresh_token' => 'ODRlMjNkNTUtNTM0Yy00ODdmLWI0Y2EtZmNiNTBkZjM4NTkzM2RhYWNiZGUtZTY0_PE93_5ac59ef2-de6b-4a10-b5c1-45abf89deb89'
                    //Cache::get('webex-access-refresh-token')
                    ];
//                }else {
//                    $body = [
//                        'grant_type' => 'authorization_code',
//                        'client_id' => config('applab-webex.client-id'),
//                        'client_secret' => config('applab-webex.client-secret'),
//                        'code' => config('applab-webex.authorized-code'),
//                        'redirect_uri' => config('applab-webex.redirect-uri')];
//                }
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
