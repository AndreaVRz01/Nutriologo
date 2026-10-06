<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CitaController extends Controller {
    public function index(Request $request) {
        $user = Auth::user();
        $fechaSeleccionada = $request->get('fecha', Carbon::today()->format('Y-m-d'));

        if ($user->isNutriologo()) {
            $citas = Cita::with('paciente')->orderBy('fecha', 'asc')->orderBy('hora_inicio', 'asc')->get();
            $pacientes = User::where('role', 'paciente')->orderBy('name')->get();
        } else {
            $citas = Cita::where('paciente_id', $user->id)->orderBy('fecha', 'asc')->get();
            $pacientes = collect();
        }

        $citasFecha = Cita::where('fecha', $fechaSeleccionada)
            ->where('estado', '!=', 'cancelada')
            ->get();

        return view('citas.index', compact('citas', 'pacientes', 'fechaSeleccionada', 'citasFecha'));
    }

    public function store(Request $request) {
        $user = Auth::user();

        $request->validate([
            'fecha' => 'required|date|after_or_equal:today',
            'hora_inicio' => 'required|string',
            'paciente_id' => $user->isNutriologo() ? 'required|exists:users,id' : 'nullable',
        ]);

        $pacienteId = $user->isNutriologo() ? $request->paciente_id : $user->id;

        $horaInicio = Carbon::parse($request->hora_inicio);
        $horaFin = (clone $horaInicio)->addHours(2);

        // Validar rango permitido 8 AM a 6 PM
        if ($horaInicio->hour < 8 || $horaFin->hour > 18 || ($horaFin->hour == 18 && $horaFin->minute > 0)) {
            return back()->with('error', 'Las citas solo pueden programarse entre las 08:00 AM y 06:00 PM.');
        }

        // Validar máximo 5 citas al día
        $citasDiaCount = Cita::where('fecha', $request->fecha)
            ->where('estado', '!=', 'cancelada')
            ->count();

        if ($citasDiaCount >= 5) {
            return back()->with('error', 'Se ha alcanzado el límite de 5 citas máximas para esta fecha.');
        }

        // Validar traslape de horario
        $traslape = Cita::where('fecha', $request->fecha)
            ->where('estado', '!=', 'cancelada')
            ->where(function ($query) use ($horaInicio, $horaFin) {
                $query->where(function ($q) use ($horaInicio, $horaFin) {
                    $q->where('hora_inicio', '<', $horaFin->format('H:i:s'))
                      ->where('hora_fin', '>', $horaInicio->format('H:i:s'));
                });
            })->exists();

        if ($traslape) {
            return back()->with('error', 'El horario seleccionado se traslapa con otra cita existente de 2 horas.');
        }

        Cita::create([
            'paciente_id' => $pacienteId,
            'fecha' => $request->fecha,
            'hora_inicio' => $horaInicio->format('H:i:s'),
            'hora_fin' => $horaFin->format('H:i:s'),
            'estado' => 'confirmada',
            'notas' => $request->notas,
        ]);

        return back()->with('success', 'Cita agendada correctamente.');
    }

    public function update(Request $request, Cita $cita) {
        $user = Auth::user();
        if (!$user->isNutriologo()) {
            return back()->with('error', 'Solo el nutriólogo puede modificar o reagendar citas.');
        }

        $request->validate([
            'fecha' => 'required|date',
            'hora_inicio' => 'required|string',
            'estado' => 'required|in:confirmada,reagendada,cancelada',
        ]);

        $horaInicio = Carbon::parse($request->hora_inicio);
        $horaFin = (clone $horaInicio)->addHours(2);

        $traslape = Cita::where('fecha', $request->fecha)
            ->where('id', '!=', $cita->id)
            ->where('estado', '!=', 'cancelada')
            ->where(function ($q) use ($horaInicio, $horaFin) {
                $q->where('hora_inicio', '<', $horaFin->format('H:i:s'))
                  ->where('hora_fin', '>', $horaInicio->format('H:i:s'));
            })->exists();

        if ($traslape) {
            return back()->with('error', 'Conflicto de horario: la hora elegida está ocupada.');
        }

        $cita->update([
            'fecha' => $request->fecha,
            'hora_inicio' => $horaInicio->format('H:i:s'),
            'hora_fin' => $horaFin->format('H:i:s'),
            'estado' => $request->estado,
            'notas' => $request->notas,
        ]);

        return back()->with('success', 'Cita actualizada y notificación registrada.');
    }

    public function destroy(Cita $cita) {
        $user = Auth::user();
        if (!$user->isNutriologo() && $cita->paciente_id !== $user->id) {
            return back()->with('error', 'No tiene permisos para cancelar esta cita.');
        }

        $cita->update(['estado' => 'cancelada']);
        return back()->with('success', 'La cita fue cancelada correctamente.');
    }

    public function cargaMasiva(Request $request) {
        $user = Auth::user();
        if (!$user->isNutriologo()) {
            return back()->with('error', 'Acceso no autorizado.');
        }

        $request->validate(['citas_json' => 'required|string']);
        $items = json_decode($request->citas_json, true);

        if (!is_array($items)) {
            return back()->with('error', 'El formato JSON ingresado no es válido.');
        }

        $creadas = 0;
        foreach ($items as $item) {
            if (isset($item['paciente_id'], $item['fecha'], $item['hora_inicio'])) {
                $hIni = Carbon::parse($item['hora_inicio']);
                $hFin = (clone $hIni)->addHours(2);

                $citasCount = Cita::where('fecha', $item['fecha'])->where('estado', '!=', 'cancelada')->count();
                if ($citasCount >= 5) continue;

                $traslape = Cita::where('fecha', $item['fecha'])
                    ->where('estado', '!=', 'cancelada')
                    ->where(function ($q) use ($hIni, $hFin) {
                        $q->where('hora_inicio', '<', $hFin->format('H:i:s'))
                          ->where('hora_fin', '>', $hIni->format('H:i:s'));
                    })->exists();

                if (!$traslape) {
                    Cita::create([
                        'paciente_id' => $item['paciente_id'],
                        'fecha' => $item['fecha'],
                        'hora_inicio' => $hIni->format('H:i:s'),
                        'hora_fin' => $hFin->format('H:i:s'),
                        'estado' => 'confirmada',
                        'notas' => $item['notas'] ?? 'Carga masiva',
                    ]);
                    $creadas++;
                }
            }
        }

        return back()->with('success', "Proceso masivo finalizado. Citas creadas: {$creadas}.");
    }
}