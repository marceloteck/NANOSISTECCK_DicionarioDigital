<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    protected $signature = 'user:create-admin
        {name : Nome do administrador}
        {email : E-mail do administrador}
        {--password= : Senha do administrador}
        {--promote-existing : Promove usuário existente em vez de falhar}';

    protected $description = 'Cria o primeiro usuário administrador de forma segura.';

    public function handle(): int
    {
        $name = (string) $this->argument('name');
        $email = mb_strtolower((string) $this->argument('email'));
        $password = (string) ($this->option('password') ?: '');

        if (strlen($password) < 8) {
            $this->error('A senha deve ter ao menos 8 caracteres (use --password).');

            return self::FAILURE;
        }

        $existing = User::query()->where('email', $email)->first();

        if ($existing) {
            if (! $this->option('promote-existing')) {
                $this->error('Já existe usuário com esse e-mail. Use --promote-existing para promover.');

                return self::FAILURE;
            }

            $existing->forceFill([
                'name' => $name,
                'password' => Hash::make($password),
                'role' => User::ROLE_ADMIN,
                'email_verified_at' => $existing->email_verified_at ?? now(),
            ])->save();

            $this->info('Usuário existente promovido para admin com sucesso.');

            return self::SUCCESS;
        }

        User::query()->create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => User::ROLE_ADMIN,
            'email_verified_at' => now(),
        ]);

        $this->info('Admin criado com sucesso.');

        return self::SUCCESS;
    }
}
