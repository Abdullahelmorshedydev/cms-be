<?php

namespace Database\Seeders;

use App\Enums\GenderEnum;
use App\Enums\StatusEnum;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate([
            'email' => 'super-admin@tasweek.com',
            'phone' => '01206296308',
        ], [
            'name' => 'Super Admin',
            'email' => 'super-admin@tasweek.com',
            'email_verified_at' => now(),
            'password' => Hash::make('SuperTasweek#1'),
            'phone' => '01206296308',
            'address' => [
                'country' => 'Egypt',
                'city' => 'Cairo',
                'street' => '95 Merghany st',
            ],
            'is_admin' => 1,
            'remember_token' => Str::random(10),
        ]);

        User::updateOrCreate([
            'email' => 'admin@tasweek.com',
            'phone' => '01206296308',
        ], [
            'name' => 'Admin',
            'email' => 'admin@tasweek.com',
            'email_verified_at' => now(),
            'password' => Hash::make('Tasweek#1'),
            'phone' => '01206296308',
            'address' => [
                'country' => 'Egypt',
                'city' => 'Cairo',
                'street' => '95 Merghany st',
            ],
            'is_admin' => 1,
            'remember_token' => Str::random(10),
        ]);
    }
}
