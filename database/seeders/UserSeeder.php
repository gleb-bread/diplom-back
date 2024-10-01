<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'login' => 'test_user',
            'name' => 'Test',
            'second_name' => 'User',
            'patronymic' => 'Testovich',
            'password' => bcrypt('password'),
            'delayed' => false,
        ]);
    }
}
