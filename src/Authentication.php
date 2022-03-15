<?php


namespace Applab\WebexMeeting;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
class Authentication extends GClient
{
    //authenticate the app api user for all requests
    public function login($code = null)
    {
        try {
            if (!config('applab-webex.client-id') || !config('applab-webex.client-secret')) {
                throw new \Exception('Invalid input!, Ensure configuration values are correct');
            }
            if ($code !== null) {
                $body = [
                    'grant_type' => 'authorization_code',
                    'client_id' => config('applab-webex.client-id'),
                    'client_secret' => config('applab-webex.client-secret'),
                    'code' => $code,
                    'redirect_uri' => config('applab-webex.redirect-uri'),
                ];
            } else {
                $body = [
                    'grant_type' => 'refresh_token',
                    'client_id' => config('applab-webex.client-id'),
                    'client_secret' => config('applab-webex.client-secret'),
                    'refresh_token' => settings()->get('applab-webex.webex-access-refresh-token'),
                ];
            }
            $response = $this->client->request('POST', 'access_token', [
                'headers' => [
                    'Content-Type' => 'application/x-www-form-urlencoded',
                ],
                'form_params' => $body
            ]);
            if ($response->getStatusCode() == 200) {
                $response = json_decode($response->getBody());
                if ($response->access_token) {
                    settings()->set('applab-webex.webex-access-token', $response->access_token);
                    settings()->set('applab-webex.webex-access-token-expires-in',
                        now()->addSeconds($response->expires_in));
                    settings()->set('applab-webex.webex-access-refresh-token', $response->refresh_token);
                    settings()->set('applab-webex.webex-access-refresh-token-expires-in',
                        now()->addSeconds($response->refresh_token_expires_in));
                    settings()->save();
                    return true;
                }
            } elseif ($response->getStatusCode() == 400) {
                settings()->set('applab-webex.webex-access-token', null);
                settings()->set('applab-webex.webex-access-token-expires-in', null);
                settings()->set('applab-webex.webex-access-refresh-token', null);
                settings()->set('applab-webex.webex-access-refresh-token-expires-in', null);
                settings()->save();
            }
            throw new \Exception("Access Token generation failed");
        } catch (GuzzleException $e) {
            \Log::critical("WEbEXAuthentication Failure ".$e->getMessage());
            throw $e;
        }
    }
}
