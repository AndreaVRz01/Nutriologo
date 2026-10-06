<?php

namespace App\Http\Controllers;

use App\Models\FormularioClinico;
use App\Models\User;
use Illuminate\Http\Request;

class FormularioClinicoController extends Controller {
    public function index() {
        $pacientes = User::where('role', 'paciente')->orderBy('name')->get();
        $formularios = FormularioClinico::with('paciente')->latest()->get();
        return view('formularios.index', compact('pacientes', 'formularios'));
    }

    public function store(Request $request) {
        $request->validate([
            'paciente_id' => 'required|exists:users,id',
            'edad' => 'required|integer|min:1|max:120',
            'peso' => 'required|numeric|min:1|max:300',
            'estatura' => 'required|numeric|min:0.5|max:2.5',
            'condicion_medica' => 'nullable|string',
            'alergias' => 'nullable|string',
            'descripcion' => 'nullable|string',
        ]);

        FormularioClinico::create($request->all());

        return back()->with('success', 'Formulario clínico registrado exitosamente.');
    }

    public function cargaMasiva(Request $request) {
        $request->validate(['formularios_json' => 'required|string']);
        $items = json_decode($request->formularios_json, true);

        if (!is_array($items)) {
            return back()->with('error', 'El JSON proporcionado no es válido.');
        }

        $guardados = 0;
        foreach ($items as $item) {
            if (isset($item['paciente_id'], $item['edad'], $item['peso'], $item['estatura'])) {
                FormularioClinico::create([
                    'paciente_id' => $item['paciente_id'],
                    'edad' => $item['edad'],
                    'peso' => $item['peso'],
                    'estatura' => $item['estatura'],
                    'condicion_medica' => $item['condicion_medica'] ?? null,
                    'alergias' => $item['alergias'] ?? null,
                    'descripcion' => $item['descripcion'] ?? null,
                ]);
                $guardados++;
            }
        }

        return back()->with('success', "Se cargaron masivamente {$guardados} formularios.");
    }
}