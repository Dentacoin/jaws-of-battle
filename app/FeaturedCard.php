<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FeaturedCard extends Model
{
    protected function mobileBackgroundMedia() {
        return $this->belongsTo('App\Media', 'mobileBackgroundMedia_id');
    }

    protected function backgroundMedia() {
        return $this->belongsTo('App\Media', 'backgroundMedia_id');
    }
}