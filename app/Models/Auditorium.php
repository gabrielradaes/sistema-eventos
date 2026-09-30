<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Auditorium extends Model
{
    // 1. Forzamos el nombre exacto de la tabla en MySQL
    protected $table = 'auditoriums';

    // 2. Permitimos la asignación masiva
    protected $fillable = ['name', 'location'];
}
