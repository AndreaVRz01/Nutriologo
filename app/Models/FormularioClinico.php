<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormularioClinico extends Model {
    protected $table = 'formularios_clinicos';

    protected $fillable = ['paciente_id', 'edad', 'peso', 'estatura', 'condicion_medica', 'alergias', 'descripcion'];

    public function paciente() {
        return $this->belongsTo(User::class, 'paciente_id');
    }
}