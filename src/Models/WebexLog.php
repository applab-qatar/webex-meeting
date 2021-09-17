<?php

namespace Applab\WebexMeeting\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class WebexLog extends Model
{
    use SoftDeletes;
    protected $fillable = ['request','event','response_id','response','loggable_type','loggable_id'];


    public function __construct() {

    }

    public function loggable():MorphTo
    {
        return $this->morphTo();
    }
}
