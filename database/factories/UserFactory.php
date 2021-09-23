<?php

namespace Database\Factories;

use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
    	return [
            'name' => "Administrator",
            'email' => 'admin@gmail.com',
            'password' => app('hash')->make('admin123')
        ];
    }
}
