<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'status',
        'requester_id',
        'auditorium_id', 
        'title', 
        'instructor_name',
        'support_staff',
        'description', 
        'capacity', 
        'start_time', 
        'end_time'
    ];
    public function equipment()
    {
        return $this->belongsToMany(Equipment::class)->withPivot('quantity_reserved');
    }

    // Esta es la función mágica que Laravel estaba buscando
    public function auditorium()
    {
        return $this->belongsTo(Auditorium::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class);
    }
    // Relación para saber qué usuario solicitó este evento
    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }
    
}

    