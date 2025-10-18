<?php

namespace Database\Factories;

use App\Models\GroupMembership;
use App\Models\User;
use App\Models\ReadingGroup;
use Illuminate\Database\Eloquent\Factories\Factory;

class GroupMembershipFactory extends Factory
{
    protected $model = GroupMembership::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'reading_group_id' => ReadingGroup::factory(),
            'role' => $this->faker->randomElement(['owner', 'admin', 'member']),
            'status' => $this->faker->randomElement(['approved', 'pending', 'banned']),
            'joined_at' => now(),
        ];
    }
}
