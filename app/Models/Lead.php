<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = ['name', 'whatsapp_number', 'email', 'major_interest', 'status', 'notes'];
}