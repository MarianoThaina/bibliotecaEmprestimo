<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmprestimoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                      => $this->id,
            'exemplar_id'             => $this->exemplar_id,
            'usuario_id'              => $this->usuario_id,
            'data_aluguel'            => $this->data_aluguel?->toDateString(),
            'data_devolucao_prevista' => $this->data_devolucao_prevista?->toDateString(),
            'data_devolucao_real'     => $this->data_devolucao_real?->toDateString(),
            'valor_diario'            => $this->valor_diario,
            'status'                  => $this->status,
            'dias_atraso'             => $this->diasAtraso(),
            'multas'                  => MultaResource::collection($this->whenLoaded('multas')),
            'created_at'              => $this->created_at,
            'updated_at'              => $this->updated_at,
        ];
    }
}
