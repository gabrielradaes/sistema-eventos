<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'start_time',
        'end_time',
        'user_name',
        'user_phone',
        'authority_name',
        'responsible',
        'salon',
        'event_type',
        'requirements',
        'capacity',
        'entry_type',
        'external_coordinator_name',
        'external_coordinator_phone',
        'special_requirements',
        'registered_by',
        'internal_coordinator',
    ];

    protected $casts = [
        'requirements' => 'array',
    ];
}