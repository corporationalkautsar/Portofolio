<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdminCommand extends Command
{
    protected $signature = 'portfolio:admin {email? : Admin email address} {name? : Admin display name}';

    protected $description = 'Create a secure administrator account for the portfolio dashboard';

    public function handle(): int
    {
        $email = trim((string) ($this->argument('email') ?: $this->ask('Admin email')));
        $name = trim((string) ($this->argument('name') ?: $this->ask('Admin name')));
        $password = trim((string) $this->secret('Password (minimum 12 characters)'));
        $confirmation = trim((string) $this->secret('Confirm password'));

        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ], [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:12'],
        ]);

        $validator->after(function ($validator) use ($confirmation, $password) {
            if ($password !== $confirmation) {
                $validator->errors()->add('password', 'The password confirmation does not match.');
            }
        });

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        User::query()->create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'is_admin' => true,
        ]);

        $this->info("Administrator created for {$email}.");

        return self::SUCCESS;
    }
}
