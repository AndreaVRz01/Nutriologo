<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use Carbon\Carbon;

class LandingController extends Controller {
    public function index() {
        $citasHoy = Cita::whereDate('fecha', Carbon::today())
            ->where('estado', '!=', 'cancelada')
            ->get();
            
        return view('landing', compact('citasHoy'));
    }
}