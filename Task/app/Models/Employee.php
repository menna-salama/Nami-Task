<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
     protected $fillable = ['name', 'salary'];

    public function workTimes() {
        return $this->hasMany(WorkTime::class, 'emp_id');
    }

    public function getHourCostAttribute()
    {
        $totalHours = $this->workTimes()->sum('hours');
        return $totalHours > 0 ? round($this->salary / $totalHours, 2) : 0;
    }
}
