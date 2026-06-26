<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class Emprestimo extends Model
{
    protected $table = 'emprestimos';

    protected $fillable = [
        'exemplar_id',
        'usuario_id',
        'data_aluguel',
        'data_devolucao_prevista',
        'data_devolucao_real',
        'valor_diario',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'data_aluguel'            => 'date',
            'data_devolucao_prevista' => 'date',
            'data_devolucao_real'     => 'date',
            'valor_diario'            => 'decimal:2',
        ];
    }

    public function multas(): HasMany
    {
        return $this->hasMany(Multa::class, 'emprestimo_id');
    }

    public function scopeVencidos(Builder $q): Builder
    {
        return $q->whereNull('data_devolucao_real')
            ->whereIn('status', ['aberto', 'atrasado'])
            ->whereDate('data_devolucao_prevista', '<', today());
    }

    public function diasAtraso(?Carbon $ref = null): int
    {
        $ref = $ref ?? $this->data_devolucao_real ?? today();
        $dias = $this->data_devolucao_prevista->diffInDays($ref, false);

        return (int) max(0, floor($dias));
    }

    public function valorMultaAtraso(?Carbon $ref = null): float
    {
        $mult = (float) config('emprestimo.multa_atraso_multiplicador', 1.0);

        return round($this->diasAtraso($ref) * (float) $this->valor_diario * $mult, 2);
    }
}
