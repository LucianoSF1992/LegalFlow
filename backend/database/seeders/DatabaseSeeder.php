<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // A conta de demonstração existe somente no ambiente local.
        if (! app()->environment('local')) {
            return;
        }

        User::firstOrCreate(
            ['email' => 'demo@legalflow.local'],
            [
                'name' => 'Usuário Demonstração',
                'password' => 'Demo@12345',
            ]
        );
    }
}