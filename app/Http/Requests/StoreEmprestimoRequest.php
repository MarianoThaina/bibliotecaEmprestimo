<?php

namespace App\Http\Requests;

use App\Models\Emprestimo;
use Illuminate\Foundation\Http\FormRequest;

class StoreEmprestimoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Emprestimo::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'exemplar_id'             => ['required', 'integer', 'exists:exemplares,id'],
            'usuario_id'              => ['required', 'integer', 'exists:usuarios,id'],
            'data_devolucao_prevista' => ['required', 'date', 'after:today'],
            'valor_diario'            => ['required', 'numeric', 'min:0'],
        ];
    }
}
