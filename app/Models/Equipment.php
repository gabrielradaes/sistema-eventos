<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    // Permitimos la asignación masiva
    protected $fillable = ['name', 'total_quantity'];

    // Relación con la tabla de eventos
    public function events()
    {
        return $this->belongsToMany(Event::class)->withPivot('quantity_reserved');
    }
}
