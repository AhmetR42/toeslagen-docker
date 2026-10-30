<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $u = User::firstOrNew(['email' => 'admin@toeslagen.nl']);
        $u->forceFill([
            'name' => 'Beheerder',
            'password' => 'Admin123!', // wordt gehasht door de 'hashed' cast
            'rol' => 'admin',
        ])->save();

        $this->call(ToeslagSeeder::class);
    }
}
