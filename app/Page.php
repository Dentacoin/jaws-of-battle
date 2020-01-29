<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected function media() {
        return $this->belongsTo('App\Media');
    }

    protected function parent() {
        return $this->belongsTo('App\PageMetaData');
    }
}
