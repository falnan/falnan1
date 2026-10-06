<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Enums\VerificationStatus;
use App\Models\Segment;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Segments
        $keluarga = Segment::firstOrCreate(
            ['slug' => 'keluarga'],
            ['name' => 'Keluarga']
        );

        $teman = Segment::firstOrCreate(
            ['slug' => 'teman'],
            ['name' => 'Teman']
        );

        // 1. Super Admin
        $superAdmin = User::create([
            'name' => 'Falnan',
            'email' => 'falnannn@gmail.com',
            'password' => bcrypt('asdfasdf'),
            'verification_status' => VerificationStatus::Approved,
        ]);

        $superAdmin->assignRole(UserRole::SuperAdmin);

        // 2. Author
        $author = User::create([
            'name' => 'Author',
            'email' => 'author@example.com',
            'password' => bcrypt('asdfasdf'),
            'verification_status' => VerificationStatus::Approved,
        ]);

        $author->assignRole(UserRole::Author);

        // 3. Member - Keluarga
        $memberKeluarga = User::create([
            'name' => 'Member Keluarga',
            'email' => 'keluarga@example.com',
            'password' => bcrypt('asdfasdf'),
            'verification_status' => VerificationStatus::Approved,
        ]);

        $memberKeluarga->assignRole(UserRole::Member);
        $memberKeluarga->segments()->attach($keluarga);

        // 4. Member - Teman
        $memberTeman = User::create([
            'name' => 'Member Teman',
            'email' => 'teman@example.com',
            'password' => bcrypt('asdfasdf'),
            'verification_status' => VerificationStatus::Pending,
        ]);

        $memberTeman->assignRole(UserRole::Member);
        $memberTeman->segments()->attach($teman);

        // 5. Member - Keluarga + Teman
        $memberKeduanya = User::create([
            'name' => 'Member Keluarga Teman',
            'email' => 'keluarga.teman@example.com',
            'password' => bcrypt('asdfasdf'),
            'verification_status' => VerificationStatus::Rejected,
        ]);

        $memberKeduanya->assignRole(UserRole::Member);
        $memberKeduanya->segments()->attach([
            $keluarga->id,
            $teman->id,
        ]);
    }
}
