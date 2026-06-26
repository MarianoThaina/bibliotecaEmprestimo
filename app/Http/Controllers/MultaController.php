<?php

namespace App\Http\Controllers;

use App\Http\Resources\MultaResource;
use App\Models\Multa;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class MultaController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $user = $request->user();
        $query = Multa::query()->with('emprestimo');

        if ($request->filled('usuario_id')) {
            $alvo = $request->integer('usuario_id');
            abort_unless($user->ehFuncionario() || $user->id === $alvo, 403);
            $query->whereHas('emprestimo', fn ($q) => $q->where('usuario_id', $alvo));
        } else {
            abort_unless($user->ehFuncionario(), 403);
        }

        return MultaResource::collection($query->latest()->paginate(20));
    }

    public function show(Multa $multa)
    {
        $this->authorize('view', $multa);

        return new MultaResource($multa->load('emprestimo'));
    }
}
