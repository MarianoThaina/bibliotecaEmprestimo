<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Multa extends Model
{
    protected $table = 'multas';

    protected $fillable = [
        'emprestimo_id',
        'tipo_multa',
        'valor',
        'observacoes',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'valor' => 'decimal:2',
        ];
    }

    public function emprestimo(): BelongsTo
    {
        return $this->belongsTo(Emprestimo::class, 'emprestimo_id');
    }
}
