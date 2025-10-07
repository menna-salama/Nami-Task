<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = ['name'];

    public function workTimes() {
        return $this->hasMany(WorkTime::class, 'project_id');
    }
}
