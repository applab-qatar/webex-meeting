<?php

namespace Applab\WebexMeeting;

use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Cache;

class WebexMeeting
{
    /*
     *
     */
    public function __construct()
    {
        if (settings()->has('applab-webex.webex-access-token-expires-in') &&
            Carbon::parse(settings()->get('applab-webex.webex-access-token-expires-in'))->lt(now())) {
            $this->authClass = new Authentication();
            $this->authClass->login();
        }
        $this->meetingClass=new Meeting();
        $this->inviteeClass=new Invitee();
        $this->participantClass=new Participants();
    }
/*
 * Single or all
 */
    public function getMeeting($meeting=null)
    {
        try{
            $response= $this->meetingClass->getMe($meeting);
            return json_decode($response);
        }catch(Exception $e){
            \Log::error("Meeting::Exception ".$e->getMessage());
            throw $e;
        }
    }

    /*
     *
     */
    public function createMeeting($event,$meeting)
    {
        try{
            if(!empty($meeting)){
                $response= $this->meetingClass->createMe($event,$meeting);
                return json_decode($response);
            }else{
                throw new Exception('Invalid input!, Ensure input(s) are correct');
            }
        }catch(Exception $e){
            \Log::error("MeetingCreation::Exception ".$e->getMessage());
            throw $e;
        }
    }
    public function updateMeeting($meetingId,$meeting)
    {
        try{
            if(!empty($meetingId) && !empty($meeting)){
                $response= $this->meetingClass->updateMe($meetingId,$meeting);
                return json_decode($response);
            }else{
                throw new Exception('Invalid input!, Ensure input(s) are correct');
            }
        }catch(Exception $e){
            \Log::error("MeetingUpdating::Exception ".$e->getMessage());
            throw $e;
        }
    }

    /*
     *
     */
    public function deleteMeeting($meetingId)
    {
        try{
            if(!empty($meetingId)){
                $response= $this->meetingClass->deleteMe($meetingId);
                return json_encode(['status'=>'success','code'=>$response]);
            }else{
                throw new Exception('Invalid input!, Ensure input(s) are correct');
            }
        }catch(Exception $e){
            \Log::error("MeetingDeletion::Exception ".$e->getMessage());
            throw $e;
        }
    }


    /*
     *
     */

    public function getInvitees($meetingID)
    {
        try{
            $response= $this->inviteeClass->getInviTes($meetingID);
            return json_decode($response);
        }catch(Exception $e){
            \Log::error("Meeting::Exception ".$e->getMessage());
            throw $e;
        }
    }
    /*
     *
     */
    public function getInvitee($inviteeID)
    {
        try{
            $response= $this->inviteeClass->getInviT($inviteeID);
            return json_decode($response);
        }catch(Exception $e){
            \Log::error("Meeting::Exception ".$e->getMessage());
            throw $e;
        }
    }
    /*
    *
    */
    public function createInvite($register,$invitee)
    {
        try{
            if(!empty($invitee)){
                $response= $this->inviteeClass->createInviT($register,$invitee);
                return json_decode($response);
            }else{
                throw new Exception('Invalid input!, Ensure input(s) are correct');
            }
        }catch(Exception $e){
            \Log::error("InviteeCreation::Exception ".$e->getMessage());
            throw $e;
        }
    }

    /*
     *
     */
    public function updateInvite($inviteeID,$invitee)
    {
        try{
            if(!empty($inviteeID) && !empty($invitee)){
                $response= $this->inviteeClass->updateInviT($inviteeID,$invitee);
                return json_decode($response);
            }else{
                throw new Exception('Invalid input!, Ensure input(s) are correct');
            }
        }catch(Exception $e){
            \Log::error("InviteeUpdating::Exception ".$e->getMessage());
            throw $e;
        }
    }

    /*
     *
     */
    public function deleteInvite($inviteeID)
    {
        try{
            if(!empty($inviteeID)){
                $response= $this->inviteeClass->deleteInviT($inviteeID);
                return json_encode(['status'=>'success','code'=>$response]);
            }else{
                throw new Exception('Invalid input!, Ensure input(s) are correct');
            }
        }catch(Exception $e){
            \Log::error("InviteeDeletion::Exception ".$e->getMessage());
            throw $e;
        }
    }

    /*
     *
     */
    public function getParticipants($meetingID)
    {
        try{
            $response= $this->participantClass->getPartcpants($meetingID);
            return json_decode($response);
        }catch(Exception $e){
            \Log::error("Meeting::Exception ".$e->getMessage());
            throw $e;
        }
    }
    /*
     *
     */
    public function getParticipant($participantId)
    {
        try{
            $response= $this->participantClass->getPartcpant($participantId);
            return json_decode($response);
        }catch(Exception $e){
            \Log::error("Meeting::Exception ".$e->getMessage());
            throw $e;
        }
    }
}
