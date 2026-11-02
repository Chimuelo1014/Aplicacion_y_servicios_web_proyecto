@php($color = ['disponible' => 'success', 'reservado' => 'warning', 'vendido' => 'secondary'][$estado] ?? 'light')
<span class="badge text-bg-{{ $color }}">{{ ucfirst($estado) }}</span>
