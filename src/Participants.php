<?php


namespace Applab\WebexMeeting;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Cache;
use Exception;
class Participants extends GClient
{
    public function getPartcpants($meetingId)
    {
        try {
            $response = $this->client->request('GET', 'meetingParticipants?meetingId='.$meetingId, [
                'headers' => [
                    'Authorization' => "Bearer " . Cache::get('webex-access-token'),
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ]
            ]);
            if ($response->getStatusCode()==200) {
                return $response->getBody();
            }
            throw new Exception("Participants Listing failed");
        } catch (GuzzleException $e) {
            throw $e;
        }
    }

    public function getPartcpant($participantId)
    {
        try {
            $response = $this->client->request('GET', 'meetingParticipants/'.$participantId, [
                'headers' => [
                    'Authorization' => "Bearer " . Cache::get('webex-access-token'),
                    'Accept' => 'application/json',
                    'Content-Type' => 'application/json',
                ]
            ]);
            if ($response->getStatusCode()==200) {
                return $response->getBody();
            }
            throw new Exception("Participants details failed");
        } catch (GuzzleException $e) {
            throw $e;
        }
    }
}
