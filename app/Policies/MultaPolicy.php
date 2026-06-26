<?php

namespace App\Policies;

use App\Models\Multa;
use App\Models\User;

class MultaPolicy
{
    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Multa $multa): bool
    {
        return $user->ehFuncionario()
            || $user->id === $multa->emprestimo->usuario_id;
    }
}
