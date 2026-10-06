<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;


    public function run(): void
    {

        Role::firstOrCreate([
            'name' => UserRole::SuperAdmin->value,
            'guard_name' => 'web',
        ]);

        Role::firstOrCreate([
            'name' => UserRole::Author->value,
            'guard_name' => 'web',
        ]);

        Role::firstOrCreate([
            'name' => UserRole::Member->value,
            'guard_name' => 'web',
        ]);

        $this->call([
            UserSeeder::class,
            PostSeeder::class,
        ]);
    }
}
