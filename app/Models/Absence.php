<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absence extends Model
{
    protected $fillable = ['picket_schedule_id', 'user_id', 'check_in_time', 'check_out_time'];

    public function picketSchedule()
    {
        return $this->belongsTo(PicketSchedule::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}