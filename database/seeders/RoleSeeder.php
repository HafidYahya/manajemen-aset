<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat role
        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'Manajer Aset']);
        Role::create(['name' => 'Petugas']);
        Role::create(['name' => 'Karyawan']);

        // Assign role ke user admin pertama
        $user = User::where('email', 'hapidyahya01@gmail.com')->first();
        if ($user) {
            $user->assignRole('Admin');
        }
        }
}
