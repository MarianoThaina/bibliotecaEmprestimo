<?php

namespace App\Console\Commands;

use App\Models\Emprestimo;
use App\Models\Multa;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ProcessarEmprestimosAtrasados extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'emprestimos:processar-atrasos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Marca empréstimos vencidos como atrasados e gera/atualiza a multa de atraso.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $hoje = today();
        $vencidos = Emprestimo::vencidos()->get();

        foreach ($vencidos as $emprestimo) {
            DB::transaction(function () use ($emprestimo, $hoje) {
                if ($emprestimo->status !== 'atrasado') {
                    $emprestimo->update(['status' => 'atrasado']);
                }

                $dias = $emprestimo->diasAtraso($hoje);

                if ($dias > 0) {
                    Multa::updateOrCreate(
                        ['emprestimo_id' => $emprestimo->id, 'tipo_multa' => 'atraso'],
                        [
                            'valor'       => $emprestimo->valorMultaAtraso($hoje),
                            'status'      => 'pendente',
                            'observacoes' => "Atraso de {$dias} dia(s) (gerado automaticamente).",
                        ]
                    );
                }
            });
        }

        $this->info("Processados: {$vencidos->count()} empréstimo(s) vencido(s).");

        return self::SUCCESS;
    }
}
