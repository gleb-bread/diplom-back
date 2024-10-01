<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        return [
            'login' => $this->faker->unique()->userName,
            'name' => $this->faker->firstName,
            'second_name' => $this->faker->lastName,
            'patronymic' => $this->faker->lastName,
            'password' => bcrypt('password'), // зашифрованный пароль
            'delayed' => false,
        ];
    }
}
