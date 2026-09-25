<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TrazabilidadStock extends Model
{
    protected $table = 'trazabilidad_stocks';
    protected $guarded = [];

    public function stock()
    {
        return $this->belongsTo(Stock::class, 'stock_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}