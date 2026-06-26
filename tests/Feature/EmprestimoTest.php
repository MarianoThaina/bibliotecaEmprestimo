<?php

namespace Tests\Feature;

use Tests\TestCase;

/**
 * TEMPLATE — roda depois que vocês criarem as migrations de emprestimos/multas
 * e as factories. Cobre a regra central de abertura + idempotência do job.
 */
class EmprestimoTest extends TestCase
{
    public function test_funcionario_abre_emprestimo_para_usuario_apto(): void
    {
        $this->markTestIncomplete('Implementar após migrations/factories.');
    }

    public function test_nao_abre_se_exemplar_ja_emprestado(): void
    {
        $this->markTestIncomplete('Esperado 422 com erro em exemplar_id.');
    }

    public function test_nao_abre_se_usuario_tem_multa_pendente(): void
    {
        $this->markTestIncomplete('Esperado 422 com erro em usuario_id.');
    }

    public function test_devolucao_com_atraso_gera_multa_unica(): void
    {
        $this->markTestIncomplete('Devolver atrasado cria 1 multa; rodar o job depois NÃO duplica.');
    }
}
