<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    protected $table = 'usuarios';

    protected $guarded = ['*'];

    protected $hidden = ['senha', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verificado' => 'boolean',
            'data_verificacao' => 'datetime',
            'data_nascimento'  => 'date',
        ];
    }

    public function getAuthPassword(): string
    {
        return $this->senha;
    }

    public function ehFuncionario(): bool
    {
        return DB::table('niveis_acesso')
            ->where('id', $this->nivel_acesso_id)
            ->where('nome', 'funcionario')
            ->exists();
    }

    public function estaApto(): bool
    {
        return $this->exists && $this->email_verificado === true;
    }
}
