<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\URL;

class Media extends Model   {
    function getLink()  {
        return \URL::to('') . UPLOADS_FRONT_END . $this->name;
    }
}
