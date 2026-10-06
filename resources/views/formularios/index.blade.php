@extends('layouts.app')

@section('content')
<div class="grid-2">
    <!-- Captura Individual (Layout Boceto) -->
    <div class="card">
        <h3 style="margin-bottom: 1.5rem;">Formulario Clínico de Paciente</h3>

        <form action="{{ route('formularios.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Paciente</label>
                <select name="paciente_id" class="form-control" required>
                    @foreach($pacientes as $paciente)
                        <option value="{{ $paciente->id }}">{{ $paciente->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid-3">
                <div class="form-group">
                    <label class="form-label">Edad</label>
                    <input type="number" name="edad" class="form-control" required min="1">
                </div>
                <div class="form-group">
                    <label class="form-label">Peso (kg)</label>
                    <input type="number" step="0.1" name="peso" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Estatura (m)</label>
                    <input type="number" step="0.01" name="estatura" class="form-control" required placeholder="1.75">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Condición Médica</label>
                <input type="text" name="condicion_medica" class="form-control">
            </div>

            <div class="form-group">
                <label class="form-label">Alergias</label>
                <input type="text" name="alergias" class="form-control">
            </div>

            <div class="form-group">
                <label class="form-label">Descripción / Observaciones</label>
                <textarea name="descripcion" class="form-control" rows="3"></textarea>
            </div>

            <button type="submit" class="btn btn-primary" style="width: 100%;">Guardar Formulario</button>
        </form>
    </div>

    <!-- Carga Masiva e Historial -->
    <div>
        <div class="card">
            <h3>Carga Masiva de Formulación Clínica</h3>
            <form action="{{ route('formularios.cargaMasiva') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label class="form-label">JSON Data</label>
                    <textarea name="formularios_json" class="form-control" rows="3" placeholder='[{"paciente_id":1, "edad":30, "peso":70, "estatura":1.7}]' required></textarea>
                </div>
                <button type="submit" class="btn btn-secondary" style="width: 100%;">Cargar Múltiples Expedientes</button>
            </form>
        </div>

        <div class="card">
            <h3>Historial Clínico Reciente</h3>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Paciente</th>
                            <th>Medidas</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($formularios as $f)
                            <tr>
                                <td>{{ $f->paciente->name }}</td>
                                <td>{{ $f->peso }} kg / {{ $f->estatura }} m</td>
                                <td>{{ $f->created_at->format('Y-m-d') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection