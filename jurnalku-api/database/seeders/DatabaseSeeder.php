<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Abdul Hadi',
            'nis' => '12345677',
            'rombel' => 'PPLG X-1',
            'rayon' => 'Cicurug 9',
            'grade' => 'X',
            'password' => Hash::make('12345678'),
        ]);

        User::create([
            'name' => 'Muhammad Fazri',
            'nis' => '12344321',
            'rombel' => 'PPLG X-2',
            'rayon' => 'Cicurug 9',
            'grade' => 'X',
            'password' => Hash::make('12345678'),
        ]);

        User::create([
            'name' => 'Zahran Fairuz Rahman',
            'nis' => '12345678',
            'rombel' => 'PPLG XI-1',
            'rayon' => 'Cicurug 9',
            'grade' => 'XI',
            'password' => Hash::make('12345678'),
        ]);
    }
}