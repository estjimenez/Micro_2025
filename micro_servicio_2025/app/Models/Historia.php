<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Historia extends Model
{
    protected $table = 'historias';

    protected $fillable = [
        'titulo',
        'descripcion',
        'responsable',
        'estado',
        'puntos',
        'fecha_creacion',
        'fecha_finalizacion',
        'sprint_id',
    ];

    // Relación con sprint
    public function sprint()
    {
        return $this->belongsTo(Sprint::class);
    }

    // Estado personalizado si lo necesitas más adelante
    public function getEstadoNombreAttribute()
    {
        switch ($this->estado) {
            case 'nueva':
                return 'Nueva';
            case 'activa':
                return 'Activa';
            case 'finalizada':
                return 'Finalizada';
            case 'impedimento':
                return 'En impedimento';
            default:
                return 'Desconocido';
        }
    }
}