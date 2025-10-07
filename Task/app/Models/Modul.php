<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Modul extends Model
{
     protected $fillable = ['name'];

    public function workTimes() {
        return $this->hasMany(WorkTime::class, 'modul_id');
    }
}
