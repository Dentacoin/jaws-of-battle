<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MenuElement extends Model
{
    protected function menu() {
        return $this->belongsTo('App\Menu');
    }
}
