<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'picket_schedule_id', 'created_by_user_id', 'social_media_status', 
        'content_title', 'content_platform', 'content_status', 
        'total_leads_followed_up', 'live_tiktok_duration_minutes', 
        'live_tiktok_notes', 'issues_encountered', 'conclusion', 'documentation_path'
    ];

    protected $casts = [
        'social_media_status' => 'array', // Casting JSON otomatis
    ];

    public function picketSchedule()
    {
        return $this->belongsTo(PicketSchedule::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }
}
