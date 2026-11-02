<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Vehiculo extends Model
{
    use HasFactory;

    public const TIPOS = ['sedan', 'hatchback', 'suv', 'camioneta', 'pickup', 'moto'];

    public const ESTADOS = ['disponible', 'reservado', 'vendido'];

    protected $fillable = [
        'marca_id', 'user_id', 'modelo', 'anio', 'placa', 'color', 'tipo',
        'kilometraje', 'precio', 'estado', 'descripcion', 'foto',
    ];

    protected $casts = [
        'anio' => 'integer',
        'kilometraje' => 'integer',
        'precio' => 'decimal:2',
    ];

    protected $appends = ['foto_url'];

    public function marca(): BelongsTo
    {
        return $this->belongsTo(Marca::class);
    }

    public function registradoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getFotoUrlAttribute(): ?string
    {
        return $this->foto ? Storage::disk('public')->url($this->foto) : null;
    }
}
