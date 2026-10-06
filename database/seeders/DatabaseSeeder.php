<?php

namespace Database\Seeders;

use App\Models\Cita;
use App\Models\FormularioClinico;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder {
    public function run(): void {
        // 1. Crear usuario Nutriólogo
        $nutriologo = User::create([
            'name' => 'Dr. Carlos Mendoza (Nutriólogo)',
            'email' => 'nutriologo@consultorio.com',
            'password' => Hash::make('password123'),
            'phone' => '5551234567',
            'role' => 'nutriologo',
        ]);

        // 2. Crear 15 Pacientes
        $pacientes = [];
        for ($i = 1; $i <= 15; $i++) {
            $pacientes[] = User::create([
                'name' => "Paciente {$i} López",
                'email' => "paciente{$i}@mail.com",
                'password' => Hash::make('password123'),
                'phone' => "55500000" . str_pad($i, 2, '0', STR_PAD_LEFT),
                'role' => 'paciente',
            ]);
        }

        // 3. Crear 15 Citas distribuidas (3 días distintos, 5 citas diarias de 2 hrs)
        $bloques = ['08:00', '10:00', '12:00', '14:00', '16:00'];
        $citaIndex = 0;

        for ($dayOffset = 1; $dayOffset <= 3; $dayOffset++) {
            $fecha = Carbon::today()->addDays($dayOffset)->format('Y-m-d');
            foreach ($bloques as $hora) {
                if ($citaIndex < 15) {
                    $paciente = $pacientes[$citaIndex];
                    $hInicio = Carbon::parse($hora);
                    $hFin = (clone $hInicio)->addHours(2);

                    Cita::create([
                        'paciente_id' => $paciente->id,
                        'fecha' => $fecha,
                        'hora_inicio' => $hInicio->format('H:i:s'),
                        'hora_fin' => $hFin->format('H:i:s'),
                        'estado' => 'confirmada',
                        'notas' => "Consulta inicial de nutrición para {$paciente->name}",
                    ]);

                    // Crear expediente clínico inicial
                    FormularioClinico::create([
                        'paciente_id' => $paciente->id,
                        'edad' => rand(20, 50),
                        'peso' => rand(60, 95),
                        'estatura' => 1.70,
                        'condicion_medica' => 'Ninguna relevante',
                        'alergias' => 'Sin alergias registradas',
                        'descripcion' => 'Paciente inicia plan de alimentación balanceado.',
                    ]);

                    $citaIndex++;
                }
            }
        }
    }
}