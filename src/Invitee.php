<?php


namespace Applab\WebexMeeting;

use Applab\WebexMeeting\Models\WebexLog;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class Invitee extends GClient
{
    public function getInviTes($meetingId)
    {
        try {
            $response = $this->client->request('GET', 'meetingInvitees?meetingId='.$meetingId, [
                'headers' => [
                    'Authorization' => "Bearer " . Cache::get('access-token'),
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ]
            ]);
            if ($response->getStatusCode()==200) {
                return $response->getBody();
            }
            throw new \Exception("Invitees Listing failed");
        } catch (GuzzleException $e) {
            throw $e;
        }
    }

    public function getInviT($inviteeID)
    {
        try {
            $response = $this->client->request('GET', 'meetingInvitees/'.$inviteeID, [
                'headers' => [
                    'Authorization' => "Bearer " . Cache::get('access-token'),
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ]
            ]);
            if ($response->getStatusCode()==200) {
                return $response->getBody();
            }
            throw new \Exception("Invitee details failed");
        } catch (GuzzleException $e) {
            throw $e;
        }
    }
    public function createInviT($register,$invitee)
    {
        try {
            $validated=Validator::make($invitee, [
                'meetingId'    => 'required',
                'email'    => 'required|email',
            ]);
            if($validated->fails()){
                \Log::error("MeetingCreation::Validation ".$validated->getMessageBag());
                throw new Exception("Invalid input!, Ensure input(s) are correct");
            }
            $body=json_encode($invitee);
            $logCreated=new WebexLog();
            $logCreated->event='create';
            $logCreated->loggable_type=get_class($register);
            $logCreated->loggable_id=$register->id;
            $logCreated->request=$body;
            $logCreated->created_at=now();
            $logCreated->save();
            $response = $this->client->request('POST', 'meetingInvitees', [
                'headers' => [
                    'Authorization' => "Bearer " . Cache::get('access-token'),
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ], 'json' => $body
            ]);
            if ($response->getStatusCode()==200) {
                if($response->getBody()) {
                    $result=json_decode($response->getBody());
                    $logCreated->response_id = $result->id;
                    $logCreated->response = $response->getBody();
                    $logCreated->save();
                }
                return $response->getBody();
            }
            throw new \Exception("Invitee Creation failed");
        } catch (GuzzleException $e) {
            throw $e;
        }
    }
    public function upateInviT($inviteeId,$invitee)
    {
        try {
            $validated=Validator::make($invitee, [
                'meetingInviteeId'    => 'required',
                'email'    => 'required|email',
            ]);
            if($validated->fails()){
                \Log::error("MeetingCreation::Validation ".$validated->getMessageBag());
                throw new Exception("Invalid input!, Ensure input(s) are correct");
            }
            $body=json_encode($invitee);
            $response = $this->client->request('PUT', 'meetingInvitees/'.$inviteeId, [
                'headers' => [
                    'Authorization' => "Bearer " . Cache::get('access-token'),
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ], 'json' => $body
            ]);
            if ($response->getBody()->getContents()) {
                return $response->getBody();
            }
            throw new \Exception("Invitee Creation failed");
        } catch (GuzzleException $e) {
            throw $e;
        }
    }

    public function deleteInviT($inviteeId)
    {
        try {
            $response = $this->client->request('DELETE', 'meetingInvitees/'.$inviteeId, [
                'headers' => [
                    'Authorization' => "Bearer " . Cache::get('access-token'),
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ]
            ]);
            if ($response->getStatusCode()==200 || $response->getStatusCode()==204) {
                return $response->getStatusCode();
            }
            throw new \Exception("Invitee Deletion failed");
        } catch (GuzzleException $e) {
            throw $e;
        }
    }
}
