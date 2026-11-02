@extends('layouts.app')

@section('title', 'Cliente API')
@section('header', 'Cliente de la API REST')

@section('content')
    <p class="text-muted">
        Esta pantalla consume la API <code>/api/vehiculos</code> desde el navegador con <code>fetch</code>,
        enviando el token en la cabecera <code>Authorization: Bearer …</code>. Genera un token en
        <a href="{{ route('profile.edit') }}">Editar perfil</a> o con <code>POST /api/login</code>.
    </p>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white fw-semibold">1. Autenticación</div>
                <div class="card-body">
                    <label class="form-label" for="token">Token Bearer</label>
                    <input type="text" id="token" class="form-control font-monospace mb-2" placeholder="1|abc123…">
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-secondary" data-accion="me">GET /api/user</button>
                        <button class="btn btn-sm btn-outline-danger" data-accion="logout">POST /api/logout</button>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white fw-semibold">2. Operaciones sobre vehículos</div>
                <div class="card-body">
                    <div class="row g-2">
                        <div class="col-4"><label class="form-label small" for="id">ID</label><input type="number" id="id" class="form-control form-control-sm"></div>
                        <div class="col-8"><label class="form-label small" for="marca">Marca</label>
                            <select id="marca" class="form-select form-select-sm">
                                @foreach ($marcas as $m)<option value="{{ $m->id }}">{{ $m->nombre }}</option>@endforeach
                            </select>
                        </div>
                        <div class="col-6"><label class="form-label small" for="modelo">Modelo</label><input id="modelo" class="form-control form-control-sm" value="Prueba API"></div>
                        <div class="col-6"><label class="form-label small" for="placa">Placa</label><input id="placa" class="form-control form-control-sm" value="API{{ random_int(100, 999) }}"></div>
                        <div class="col-4"><label class="form-label small" for="anio">Año</label><input type="number" id="anio" class="form-control form-control-sm" value="{{ date('Y') }}"></div>
                        <div class="col-4"><label class="form-label small" for="km">Km</label><input type="number" id="km" class="form-control form-control-sm" value="0"></div>
                        <div class="col-4"><label class="form-label small" for="precio">Precio</label><input type="number" id="precio" class="form-control form-control-sm" value="50000000"></div>
                    </div>
                    <div class="d-flex flex-wrap gap-2 mt-3">
                        <button class="btn btn-sm btn-success" data-accion="listar">GET</button>
                        <button class="btn btn-sm btn-primary" data-accion="crear">POST</button>
                        <button class="btn btn-sm btn-warning" data-accion="actualizar">PUT</button>
                        <button class="btn btn-sm btn-danger" data-accion="eliminar">DELETE</button>
                    </div>
                    <div class="form-text">PUT y DELETE usan el ID. GET sin ID lista todos.</div>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white d-flex justify-content-between">
                    <span class="fw-semibold">Respuesta</span>
                    <span id="estadoHttp" class="badge text-bg-light">—</span>
                </div>
                <div class="card-body">
                    <div id="peticion" class="small font-monospace text-muted mb-2"></div>
                    <pre id="respuesta" class="bg-dark text-light p-3 rounded small mb-0" style="max-height: 520px; overflow: auto">Aún no hay peticiones.</pre>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const API = @json(url('/api'));
        const $ = id => document.getElementById(id);

        async function llamar(metodo, ruta, cuerpo = null) {
            const opciones = {
                method: metodo,
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + $('token').value.trim(),
                },
            };
            if (cuerpo) opciones.body = JSON.stringify(cuerpo);

            $('peticion').textContent = `${metodo} ${API}${ruta}`;
            const res = await fetch(API + ruta, opciones);
            const texto = await res.text();

            const badge = $('estadoHttp');
            badge.textContent = `${res.status} ${res.statusText}`;
            badge.className = 'badge ' + (res.ok ? 'text-bg-success' : 'text-bg-danger');
            try { $('respuesta').textContent = texto ? JSON.stringify(JSON.parse(texto), null, 2) : '(sin contenido)'; }
            catch { $('respuesta').textContent = texto; }
        }

        const datosVehiculo = () => ({
            marca_id: Number($('marca').value),
            modelo: $('modelo').value,
            placa: $('placa').value,
            anio: Number($('anio').value),
            tipo: 'sedan',
            kilometraje: Number($('km').value),
            precio: Number($('precio').value),
        });

        const acciones = {
            me: () => llamar('GET', '/user'),
            logout: () => llamar('POST', '/logout'),
            listar: () => llamar('GET', $('id').value ? `/vehiculos/${$('id').value}` : '/vehiculos'),
            crear: () => llamar('POST', '/vehiculos', datosVehiculo()),
            actualizar: () => llamar('PUT', `/vehiculos/${$('id').value}`, datosVehiculo()),
            eliminar: () => llamar('DELETE', `/vehiculos/${$('id').value}`),
        };

        document.querySelectorAll('[data-accion]').forEach(btn =>
            btn.addEventListener('click', () => acciones[btn.dataset.accion]().catch(e => $('respuesta').textContent = e))
        );
    </script>
@endpush
