<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable {
    protected $fillable = ['name', 'email', 'password', 'phone', 'role'];

    protected $hidden = ['password', 'remember_token'];

    public function isNutriologo(): bool {
        return $this->role === 'nutriologo';
    }

    public function citas() {
        return $this->hasMany(Cita::class, 'paciente_id');
    }

    public function formulariosClinicos() {
        return $this->hasMany(FormularioClinico::class, 'paciente_id');
    }
}