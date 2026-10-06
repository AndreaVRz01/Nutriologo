@extends('layouts.app')

@section('content')
<div class="grid-2">
    <!-- Panel Izquierdo: Calendario y Listado de Citas -->
    <div class="card">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem;">
            <h3>Calendario de Citas</h3>
            @if(auth()->user()->isNutriologo())
                <button id="open-masivo-modal" class="btn btn-secondary">Carga Masiva (JSON)</button>
            @endif
        </div>

        <form method="GET" action="{{ route('citas.index') }}" style="margin-bottom: 1.5rem; display: flex; gap: 1rem;">
            <input type="date" name="fecha" value="{{ $fechaSeleccionada }}" class="form-control">
            <button type="submit" class="btn btn-primary">Filtrar Día</button>
        </form>

        <h4>Citas Agendadas para {{ $fechaSeleccionada }}</h4>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Hora</th>
                        <th>Paciente</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($citasFecha as $cita)
                        <tr>
                            <td>{{ substr($cita->hora_inicio, 0, 5) }} - {{ substr($cita->hora_fin, 0, 5) }}</td>
                            <td>{{ $cita->paciente->name }}</td>
                            <td><span class="btn btn-accent" style="padding: 0.2rem 0.5rem; font-size: 0.8rem;">{{ $cita->estado }}</span></td>
                            <td>
                                <form action="{{ route('citas.destroy', $cita->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" style="padding: 0.3rem 0.6rem; font-size: 0.8rem;">Cancelar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">No hay citas registradas para esta fecha.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Panel Derecho: Selector Día / Hora del Boceto -->
    <div class="card">
        <h3>Agendar Nueva Cita</h3>
        <p style="font-size: 0.85rem; margin-bottom: 1rem; color: var(--dusty-olive);">
            Bloques obligatorios de 2 horas. Horario permitido: 08:00 AM - 06:00 PM (máx. 5 citas/día).
        </p>

        <form action="{{ route('citas.store') }}" method="POST">
            @csrf
            @if(auth()->user()->isNutriologo())
                <div class="form-group">
                    <label class="form-label">Seleccionar Paciente</label>
                    <select name="paciente_id" class="form-control" required>
                        @foreach($pacientes as $p)
                            <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->phone }})</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="form-group">
                <label class="form-label">Día de la Cita</label>
                <input type="date" name="fecha" class="form-control" value="{{ $fechaSeleccionada }}" min="{{ date('Y-m-d') }}" required>
            </div>

            <div class="form-group">
                <label class="form-label">Hora de Inicio (Bloque de 2 hrs)</label>
                <select name="hora_inicio" class="form-control" required>
                    <option value="08:00">08:00 AM (08:00 - 10:00)</option>
                    <option value="10:00">10:00 AM (10:00 - 12:00)</option>
                    <option value="12:00">12:00 PM (12:00 - 14:00)</option>
                    <option value="14:00">02:00 PM (14:00 - 16:00)</option>
                    <option value="16:00">04:00 PM (16:00 - 18:00)</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Notas Adicionales</label>
                <textarea name="notas" class="form-control" rows="2"></textarea>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">Confirmar Cita</button>
        </form>
    </div>
</div>

<!-- Modal para Carga Masiva (Exclusivo Nutriólogo) -->
<div id="masivo-modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); justify-content:center; align-items:center;">
    <div class="card" style="width:500px; max-width:90%;">
        <h3>Carga Masiva de Citas (JSON)</h3>
        <form action="{{ route('citas.cargaMasiva') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Formato: [{"paciente_id":1, "fecha":"2026-10-10", "hora_inicio":"08:00"}]</label>
                <textarea name="citas_json" class="form-control" rows="6" required></textarea>
            </div>
            <div style="display:flex; justify-end; gap:1rem;">
                <button type="button" id="close-masivo-modal" class="btn btn-secondary">Cerrar</button>
                <button type="submit" class="btn btn-primary">Procesar Carga</button>
            </div>
        </form>
    </div>
</div>
@endsection