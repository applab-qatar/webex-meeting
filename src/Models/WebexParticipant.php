<?php

namespace Applab\WebexMeeting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class WebexParticipant extends Model
{
    protected $fillable = ['meeting_id','participants'];


    public function __construct() {

    }

    public function meeting()
    {
        return $this->belongsTo(WebexLog::class);
    }
}
