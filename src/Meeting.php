<?php


namespace Applab\WebexMeeting;

use Applab\WebexMeeting\Models\WebexLog;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Exception;
class Meeting extends GClient
{
    public function getMe($meetingId)
    {
        try {
            $response = $this->client->request('GET', 'meetings/'.$meetingId, [
                'headers' => [
                    'Authorization' => "Bearer " . settings()->get('applab-webex.webex-access-token'),
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
            $logCreated=$this->logEntry('create',$event,$body);
            $response = $this->client->request('POST', 'meetings', [
                'headers' => [
                    'Authorization' => "Bearer " . settings()->get('applab-webex.webex-access-token'),
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ], 'json' => $meeting
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
            throw new Exception("Meeting Creation failed");
        } catch (GuzzleException $e) {
            throw $e;
        }
    }
    public function updateMe($meetingId,$meeting)
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
            $WebexLog=WebexLog::where('response_id', $meetingId)->where('event','create')->first();
            $logCreated = $WebexLog->replicate()->fill([
                'event' => 'update',
                'request' => $body,
                'response_id'=>''
            ]);
            $response = $this->client->request('PUT', 'meetings/'.$meetingId, [
                'headers' => [
                    'Authorization' => "Bearer " . settings()->get('applab-webex.webex-access-token'),
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ], 'json' => $meeting
            ]);
            if ($response->getBody()->getContents()) {
                if ($response->getStatusCode()==200) {
                    $result=json_decode($response->getBody());
                    $logCreated->response_id = $result->id;
                    $logCreated->response = $response->getBody();
                    $logCreated->save();
                }
                return $response->getBody();
            }
            throw new Exception("Meeting Creation failed");
        } catch (GuzzleException $e) {
            throw $e;
        }
    }

    public function deleteMe($meetingId)
    {
        try {
            $response = $this->client->request('DELETE', 'meetings/'.$meetingId, [
                'headers' => [
                    'Authorization' => "Bearer " . settings()->get('applab-webex.webex-access-token'),
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ]
            ]);
            if ($response->getStatusCode()==204) {
                WebexLog::where('response_id',$meetingId)->delete();
                return $response->getStatusCode();
            }
            throw new Exception("Meeting Deletion failed");
        } catch (GuzzleException $e) {
            throw $e;
        }
    }
    private function logEntry($logType,$model,$request)
    {
        $logCreated=new WebexLog();
        $logCreated->event=$logType;
        $logCreated->loggable_type=$model->getMorphClass();
        $logCreated->loggable_id=$model->id;
        $logCreated->request=$request;
        //$logCreated->created_at=now();
        $logCreated->save();
        return $logCreated;
    }
}
