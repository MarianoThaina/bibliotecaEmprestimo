<?php

namespace App\Http\Controllers;

use App\Http\Requests\DevolucaoRequest;
use App\Http\Requests\StoreEmprestimoRequest;
use App\Http\Resources\EmprestimoResource;
use App\Models\Emprestimo;
use App\Services\AbrirEmprestimoService;
use App\Services\DevolucaoService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class EmprestimoController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $user = $request->user();
        $query = Emprestimo::query()->with('multas');

        if ($request->filled('usuario_id')) {
            $alvo = $request->integer('usuario_id');
            abort_unless($user->ehFuncionario() || $user->id === $alvo, 403);
            $query->where('usuario_id', $alvo);
        } else {
            abort_unless($user->ehFuncionario(), 403);
        }

        return EmprestimoResource::collection($query->latest()->paginate(20));
    }

    public function show(Emprestimo $emprestimo)
    {
        $this->authorize('view', $emprestimo);

        return new EmprestimoResource($emprestimo->load('multas'));
    }

    public function store(StoreEmprestimoRequest $request, AbrirEmprestimoService $service)
    {
        $emprestimo = $service->executar($request->validated());

        return (new EmprestimoResource($emprestimo))
            ->response()
            ->setStatusCode(201);
    }

    public function devolucao(DevolucaoRequest $request, Emprestimo $emprestimo, DevolucaoService $service)
    {
        $emprestimo = $service->executar($emprestimo, $request->input('data_devolucao_real'));

        return new EmprestimoResource($emprestimo);
    }
}
