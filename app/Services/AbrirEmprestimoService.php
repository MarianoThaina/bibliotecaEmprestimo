<?php

namespace App\Services;

use App\Models\Emprestimo;
use App\Models\Multa;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AbrirEmprestimoService
{
    public function executar(array $dados): Emprestimo
    {
        return DB::transaction(function () use ($dados) {
            $erros = [];

            $ocupado = Emprestimo::where('exemplar_id', $dados['exemplar_id'])
                ->where('status', 'aberto')
                ->lockForUpdate()
                ->exists();

            if ($ocupado) {
                $erros['exemplar_id'][] = 'Exemplar já está emprestado.';
            }

            $usuario = User::find($dados['usuario_id']);
            if (! $usuario || ! $usuario->estaApto()) {
                $erros['usuario_id'][] = 'Usuário não está apto (e-mail não verificado).';
            }

            if ($this->temMultaPendente((int) $dados['usuario_id'])) {
                $erros['usuario_id'][] = 'Usuário possui multa pendente.';
            }

            if ($erros) {
                throw ValidationException::withMessages($erros);
            }

            return Emprestimo::create([
                'exemplar_id'             => $dados['exemplar_id'],
                'usuario_id'              => $dados['usuario_id'],
                'data_aluguel'            => today(),
                'data_devolucao_prevista' => $dados['data_devolucao_prevista'],
                'valor_diario'            => $dados['valor_diario'],
                'status'                  => 'aberto',
            ]);
        });
    }

    protected function temMultaPendente(int $usuarioId): bool
    {
        return Multa::where('status', 'pendente')
            ->whereHas('emprestimo', fn ($q) => $q->where('usuario_id', $usuarioId))
            ->exists();
    }
}
