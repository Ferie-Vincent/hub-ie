<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            RolesSeeder::class,
            WorkshopsSeeder::class,
            PartnersSeeder::class,
            FaqSeeder::class,
            SettingsSeeder::class,
        ]);

        // Super admin
        $admin = User::firstOrCreate(
            ['email' => 'admin@hubimportexport.ci'],
            [
                'first_name' => 'Admin',
                'last_name'  => 'DGCE',
                'password'   => Hash::make('password'),
                'is_active'  => true,
            ]
        );
        $admin->assignRole('super_admin');

        // Demo data en local uniquement
        if (app()->environment('local')) {
            $this->call(DemoDataSeeder::class);
        }
    }
}
