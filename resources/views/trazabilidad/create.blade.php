@extends('layouts.app')

@section('title', 'Crear caja')

@section('contenido')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            
            <div class="mb-4">
                <a href="{{ route('trazabilidad.index') }}" class="text-decoration-none text-secondary">
                    <i data-lucide="arrow-left" class="d-inline-block mr-1" style="width: 16px; height: 16px;"></i> Volver al listado
                </a>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white border-bottom-0 pt-4 pb-0">
                    <h3 class="mb-0 text-primary">
                        <i data-lucide="box" class="d-inline-block mr-2 text-primary"></i>Alta de Nueva Caja Quirúrgica
                    </h3>
                </div>
                
                <div class="card-body p-4">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('trazabilidad.store') }}" method="POST">
                        @csrf
                        
                        <div class="form-group mb-3">
                            <label for="codigo" class="font-weight-bold text-secondary">Código de la Caja</label>
                            <input type="text" name="codigo" id="codigo" class="form-control" placeholder="Ej: CAJ-005" value="{{ old('codigo') }}" required>
                            <small class="text-muted">Debe ser un código único que identifique la caja física.</small>
                        </div>

                        <div class="form-group mb-4">
                            <label for="nombre" class="font-weight-bold text-secondary">Nombre descriptivo</label>
                            <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Ej: Caja de Traumatología Menor" value="{{ old('nombre') }}" required>
                        </div>
                        <!-- NUEVO CAMPO: Tipo de Esterilización -->
                        <div class="mb-3">
                            <label class="form-label font-weight-bold" style="color: #495057;">Tipo de Esterilización Requerida</label>
                            <select name="tipo_esterilizacion" class="form-select" style="border-radius: 5px; padding: 10px;" required>
                                <option value="" disabled selected>Seleccione el método...</option>
                                <option value="Autoclave" {{ old('tipo_esterilizacion', isset($caja) ? $caja->tipo_esterilizacion : '') == 'Autoclave' ? 'selected' : '' }}>
                                    Autoclave 
                                </option>
                                <option value="Óxido de Etileno" {{ old('tipo_esterilizacion', isset($caja) ? $caja->tipo_esterilizacion : '') == 'Óxido de Etileno' ? 'selected' : '' }}>
                                    Óxido de Etileno
                                </option>
                            </select>
                            <small class="text-muted d-block mt-1">Defina el proceso adecuado según el material de la caja.</small>
                        </div>
                        <div class="mb-4">
                            <label for="descripcion" class="form-label text-secondary font-weight-bold" style="font-size: 0.95rem;">
                             Descripción y Contenido de la Caja
                            </label>
                            <textarea class="form-control" id="descripcion" name="descripcion" rows="3" placeholder="Ej: 2 pinzas de .., 1 tijera, 4 pinzas..."></textarea>
                            <small class="text-muted" style="font-size: 0.8rem;">Detalle manual de los instrumentos o instrumental específico que contiene.</small>
                        </div>

                        <div class="d-flex justify-content-end">
                            <a href="{{ route('trazabilidad.index') }}" class="btn btn-light mr-2">Cancelar</a>
                            <button type="submit" class="btn btn-primary px-4">Guardar Caja</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        if(typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    });
</script>
@endsection