<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cita extends Model {
    protected $fillable = ['paciente_id', 'fecha', 'hora_inicio', 'hora_fin', 'estado', 'notas'];

    public function paciente() {
        return $this->belongsTo(User::class, 'paciente_id');
    }
}