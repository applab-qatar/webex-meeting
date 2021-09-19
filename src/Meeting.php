<?php


namespace Applab\WebexMeeting;

use Applab\WebexMeeting\Models\WebexLog;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;

class Meeting extends GClient
{
    public function getMe($meetingId)
    {
        try {
            $response = $this->client->request('GET', 'meetings/'.$meetingId, [
                'headers' => [
                    'Authorization' => "Bearer " . Cache::get('webex-access-token'),
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ]
            ]);
            if ($response->getStatusCode()==200) {
                return $response->getBody();
            }
            throw new \Exception("Meeting Listing failed");
        } catch (GuzzleException $e) {
            throw $e;
        }
    }
    public function createMe($event,$meeting)
    {
        try {
            $validated=Validator::make($meeting, [
                'title'    => 'required|string|max:128',
                'start'    => 'required|before:end|date_format:Y-m-d H:i:s',
                'end'    => 'required|after:start|date_format:Y-m-d H:i:s',
            ]);
            if($validated->fails()){
                \Log::error("MeetingCreation::Validation ".$validated->getMessageBag());
                throw new Exception("Invalid input!, Ensure input(s) are correct");
            }
            $body=json_encode($meeting);
            $logCreated=new WebexLog();
            $logCreated->event='create';
            $logCreated->loggable_type=get_class($event);
            $logCreated->loggable_id=$event->id;
            $logCreated->request=$body;
            $logCreated->created_at=now();
            $logCreated->save();
            $response = $this->client->request('POST', 'meetings', [
                'headers' => [
                    'Authorization' => "Bearer " . Cache::get('webex-access-token'),
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
            throw new \Exception("Meeting Creation failed");
        } catch (GuzzleException $e) {
            throw $e;
        }
    }public function updateMe($meetingId,$meeting)
    {
        try {
            $validated=Validator::make($meeting, [
                'title'    => 'required|string|max:128',
                'start'    => 'required|before:end|date_format:Y-m-d H:i:s',
                'end'    => 'required|after:start|date_format:Y-m-d H:i:s',
            ]);
            if($validated->fails()){
                \Log::error("MeetingCreation::Validation ".$validated->getMessageBag());
                throw new Exception("Invalid input!, Ensure input(s) are correct");
            }
            $body=json_encode($meeting);
            $response = $this->client->request('PUT', 'meetings/'.$meetingId, [
                'headers' => [
                    'Authorization' => "Bearer " . Cache::get('webex-access-token'),
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ], 'json' => $body
            ]);
            if ($response->getBody()->getContents()) {
                return $response->getBody();
            }
            throw new \Exception("Meeting Creation failed");
        } catch (GuzzleException $e) {
            throw $e;
        }
    }

    public function deleteMe($meetingId)
    {
        try {
            $response = $this->client->request('DELETE', 'meetings/'.$meetingId, [
                'headers' => [
                    'Authorization' => "Bearer " . Cache::get('webex-access-token'),
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ]
            ]);
            if ($response->getStatusCode()==204) {
                WebexLog::where('response_id',$meetingId)->delete();
                return $response->getStatusCode();
            }
            throw new \Exception("Meeting Deletion failed");
        } catch (GuzzleException $e) {
            throw $e;
        }
    }
}
