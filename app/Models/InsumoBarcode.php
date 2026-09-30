<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InsumoBarcode extends Model
{
    use HasFactory;

    protected $table = 'insumo_barcodes';

    protected $fillable = [
        'barcode',
        'medicamento_id',
        'lote',
        'fecha_vencimiento',
        'cantidad_referencia',
    ];

    protected $casts = [
        'fecha_vencimiento' => 'date',
    ];

    public function medicamento()
    {
        return $this->belongsTo(Medicamento::class, 'medicamento_id');
    }
}
