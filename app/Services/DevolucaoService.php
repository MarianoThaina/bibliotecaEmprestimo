<?php

namespace App\Services;

use App\Models\Emprestimo;
use App\Models\Multa;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class DevolucaoService
{
    public function executar(Emprestimo $emprestimo, ?string $dataReal = null): Emprestimo
    {
        return DB::transaction(function () use ($emprestimo, $dataReal) {
            if ($emprestimo->status === 'devolvido') {
                throw ValidationException::withMessages([
                    'emprestimo' => ['Empréstimo já foi devolvido.'],
                ]);
            }

            $ref = $dataReal ? Carbon::parse($dataReal) : today();

            $emprestimo->data_devolucao_real = $ref;
            $emprestimo->status = 'devolvido';
            $emprestimo->save();

            $dias = $emprestimo->diasAtraso($ref);

            if ($dias > 0) {
                Multa::updateOrCreate(
                    ['emprestimo_id' => $emprestimo->id, 'tipo_multa' => 'atraso'],
                    [
                        'valor'       => $emprestimo->valorMultaAtraso($ref),
                        'status'      => 'pendente',
                        'observacoes' => "Atraso de {$dias} dia(s).",
                    ]
                );
            }

            return $emprestimo->load('multas');
        });
    }
}
