<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attandence extends Model
{
    protected $table = 'attandence';

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
