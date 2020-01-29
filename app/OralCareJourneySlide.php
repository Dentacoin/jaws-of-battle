<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OralCareJourneySlide extends Model
{
    protected function media() {
        return $this->belongsTo('App\Media');
    }
}
