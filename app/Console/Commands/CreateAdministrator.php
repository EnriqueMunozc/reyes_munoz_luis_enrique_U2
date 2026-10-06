<?php

namespace App\Console\Commands;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdministrator extends Command
{
    protected $signature = 'store:create-administrator';

    protected $description = 'Create an administrator account through interactive prompts.';

    public function handle(): int
    {
        $name = trim((string) $this->ask('Nombre'));
        $email = trim((string) $this->ask('Correo electronico'));
        $password = (string) $this->secret('Contrasena');
        $passwordConfirmation = (string) $this->secret('Confirmar contrasena');

        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'password_confirmation' => $passwordConfirmation,
        ], [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $administrator = new User;
        $administrator->name = $name;
        $administrator->email = $email;
        $administrator->password = Hash::make($password);
        $administrator->role = UserRole::Administrator;
        $administrator->save();

        $this->info('Administrador creado correctamente.');

        return self::SUCCESS;
    }
}
