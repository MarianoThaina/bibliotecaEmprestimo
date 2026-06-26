<?php

use App\Http\Controllers\EmprestimoController;
use App\Http\Controllers\MultaController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/emprestimos', [EmprestimoController::class, 'index']);
    Route::get('/emprestimos/{emprestimo}', [EmprestimoController::class, 'show']);
    Route::post('/emprestimos', [EmprestimoController::class, 'store']);
    Route::post('/emprestimos/{emprestimo}/devolucao', [EmprestimoController::class, 'devolucao']);

    Route::get('/multas', [MultaController::class, 'index']);
    Route::get('/multas/{multa}', [MultaController::class, 'show']);
});
