<?php

namespace App\Policies;

use App\Models\Emprestimo;
use App\Models\User;

class EmprestimoPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Emprestimo $emprestimo): bool
    {
        return $user->ehFuncionario() || $user->id === $emprestimo->usuario_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->ehFuncionario();
    }

    public function devolver(User $user, Emprestimo $emprestimo): bool
    {
        return $user->ehFuncionario();
    }
}
