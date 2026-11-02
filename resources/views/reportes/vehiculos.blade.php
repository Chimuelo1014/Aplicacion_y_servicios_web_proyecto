<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Inventario de vehículos</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #222; }
        .encabezado { border-bottom: 3px solid #1f3a5f; padding-bottom: 8px; margin-bottom: 12px; }
        .encabezado h1 { color: #1f3a5f; font-size: 18px; margin: 0; }
        .encabezado small { color: #666; }
        table { width: 100%; border-collapse: collapse; }
        th { background: #1f3a5f; color: #fff; padding: 6px; text-align: left; }
        td { padding: 5px 6px; border-bottom: 1px solid #ddd; }
        tr:nth-child(even) td { background: #f4f6fa; }
        .der { text-align: right; }
        .estado { padding: 2px 6px; border-radius: 3px; color: #fff; font-size: 9px; }
        .disponible { background: #2a9d8f; }
        .reservado { background: #f4a259; color: #222; }
        .vendido { background: #6c757d; }
        .total { margin-top: 12px; font-size: 12px; text-align: right; }
    </style>
</head>
<body>
    <div class="encabezado">
        <h1>{{ config('app.name') }} · Inventario de vehículos</h1>
        <small>Generado el {{ $generado->format('d/m/Y H:i') }} · {{ $vehiculos->count() }} vehículos</small>
    </div>

    <table>
        <thead>
            <tr>
                <th>Placa</th><th>Marca</th><th>Modelo</th><th>Año</th><th>Tipo</th>
                <th>Color</th><th class="der">Km</th><th class="der">Precio</th><th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($vehiculos as $v)
                <tr>
                    <td>{{ $v->placa }}</td>
                    <td>{{ $v->marca->nombre }}</td>
                    <td>{{ $v->modelo }}</td>
                    <td>{{ $v->anio }}</td>
                    <td>{{ ucfirst($v->tipo) }}</td>
                    <td>{{ $v->color }}</td>
                    <td class="der">{{ number_format($v->kilometraje, 0, ',', '.') }}</td>
                    <td class="der">$ {{ number_format($v->precio, 0, ',', '.') }}</td>
                    <td><span class="estado {{ $v->estado }}">{{ ucfirst($v->estado) }}</span></td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p class="total"><strong>Valor del inventario no vendido:</strong> $ {{ number_format($total, 0, ',', '.') }}</p>
</body>
</html>
