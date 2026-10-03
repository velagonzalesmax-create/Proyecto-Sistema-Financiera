<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoRecurrente extends Model
{
    use HasFactory;

    protected $table = 'pagos_recurrentes';

    protected $fillable = [
        'user_id',
        'categoria_id',
        'nombre',
        'monto',
        'frecuencia',
        'fecha_vencimiento',
        'dias_preaviso',
        'estado',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
}