@extends('layouts.guest')

@section('content')
<div style="padding: 1.5rem; max-width: 1200px; margin: 0 auto;">
    <!-- Barra Superior / Menu Boceto -->
    <header class="card" style="display: flex; justify-content: space-between; align-items: center;">
        <h1 style="color: var(--dusty-grape);">NutriSalud</h1>
        <div style="display: flex; gap: 1rem;">
            <button id="theme-toggle" class="btn btn-secondary">
                <svg class="icon-svg" viewBox="0 0 24 24"><path d="M12 3a9 9 0 109 9c0-.46-.04-.92-.1-1.36a5.389 5.389 0 01-4.4 2.26 5.403 5.403 0 01-5.4-5.4c0-1.81.89-3.42 2.26-4.4C12.92 3.04 12.46 3 12 3z"/></svg>
            </button>
            <a href="{{ route('login') }}" class="btn btn-primary">Inicio de Sesión</a>
        </div>
    </header>

    <!-- Contenido Principal Boceto (Información + Calendario) -->
    <div class="grid-2">
        <div class="card">
            <h2 style="margin-bottom: 1rem; color: var(--dusty-grape);">Planes Nutricionales Personalizados</h2>
            <p style="line-height: 1.6; margin-bottom: 1rem;">
                Transforma tu salud con asesoría especializada personalizada. Evaluación de composición corporal, diseño de planes alimenticios adaptados a tus metas, acompañamiento clínico continuo y control de hábitos.
            </p>
            <p style="line-height: 1.6; margin-bottom: 1.5rem;">
                Atención directa nutriólogo-paciente. Reserva tu cita en nuestro horario exclusivo de 08:00 AM a 06:00 PM.
            </p>
            <a href="{{ route('login') }}" class="btn btn-accent">Agendar Mi Cita Ahora</a>
        </div>

        <div class="card">
            <h3 style="margin-bottom: 1rem;">Vista Previa Citas Hoy</h3>
            <p style="font-size: 0.9rem; margin-bottom: 1rem;">Horarios ocupados para el día de hoy:</p>
            @if($citasHoy->isEmpty())
                <p style="color: var(--dusty-olive);">Todas las franjas de 2 horas disponibles hoy.</p>
            @else
                <ul style="list-style: none; display: flex; flex-direction: column; gap: 0.5rem;">
                    @foreach($citasHoy as $cita)
                        <li style="padding: 0.5rem; background-color: var(--bg-color); border-radius: 8px;">
                            Ocupado: {{ substr($cita->hora_inicio, 0, 5) }} - {{ substr($cita->hora_fin, 0, 5) }} hrs
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </div>

    <!-- Footer Boceto -->
    <footer class="card" style="text-align: center; margin-top: 2rem;">
        <p>&copy; 2026 Consultorio Especializado de Nutrición. Todos los derechos reservados.</p>
    </footer>
</div>
@endsection