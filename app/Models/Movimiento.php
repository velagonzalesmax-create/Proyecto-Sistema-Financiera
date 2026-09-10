<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'categoria_id', 'monto', 'tipo', 'descripcion', 'fecha'];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class);
    }
}