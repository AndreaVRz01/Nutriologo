@extends('layouts.guest')

@section('content')
<div class="auth-wrapper">
    <div class="card auth-card">
        <h2 style="text-align: center; margin-bottom: 1.5rem; color: var(--dusty-grape);">Iniciar Sesión / Registro</h2>

        @if($errors->any())
            <div class="alert alert-danger">
                {{ $errors->first() }}
            </div>
        @endif

        <form action="{{ route('login.post') }}" method="POST" style="margin-bottom: 2rem;">
            @csrf
            <div class="form-group">
                <label class="form-label">Correo Electrónico</label>
                <input type="email" name="email" class="form-control" required placeholder="correo@ejemplo.com">
            </div>
            <div class="form-group">
                <label class="form-label">Contraseña</label>
                <input type="password" name="password" class="form-control" required placeholder="******">
            </div>
            <button type="submit" class="btn btn-primary" style="width: 100%;">Entrar al Sistema</button>
        </form>

        <hr style="border: none; border-top: 1px solid var(--border-color); margin: 1.5rem 0;">

        <h3 style="font-size: 1.1rem; margin-bottom: 1rem;">¿Nuevo Paciente? Regístrate</h3>
        <form action="{{ route('register.post') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Nombre Completo</label>
                <input type="text" name="name" class="form-control" required placeholder="Tu Nombre">
            </div>
            <div class="form-group">
                <label class="form-label">Correo</label>
                <input type="email" name="email" class="form-control" required placeholder="paciente@ejemplo.com">
            </div>
            <div class="form-group">
                <label class="form-label">Teléfono</label>
                <input type="text" name="phone" class="form-control" required placeholder="5512345678">
            </div>
            <div class="form-group">
                <label class="form-label">Contraseña</label>
                <input type="password" name="password" class="form-control" required placeholder="******">
            </div>
            <button type="submit" class="btn btn-secondary" style="width: 100%;">Crear Cuenta y Agendar</button>
        </form>
    </div>
</div>
@endsection