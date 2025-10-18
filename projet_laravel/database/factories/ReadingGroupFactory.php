<?php

namespace Database\Factories;

use App\Models\ReadingGroup;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReadingGroupFactory extends Factory
{
    protected $model = ReadingGroup::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->paragraph(),
            'owner_id' => User::factory(),
            'is_private' => $this->faker->boolean(30),
        ];
    }
}
