<?php

namespace Database\Factories;

use App\Models\GroupEvent;
use App\Models\ReadingGroup;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class GroupEventFactory extends Factory
{
    protected $model = GroupEvent::class;

    public function definition(): array
    {
        $futureDate = $this->faker->dateTimeBetween('now', '+60 days');
        
        return [
            'reading_group_id' => ReadingGroup::factory(),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'event_date' => $futureDate->format('Y-m-d'),
            'event_time' => $this->faker->time('H:i'),
            'location' => $this->faker->randomElement([
                'Central Library',
                'Online via Zoom',
                'Coffee Shop Downtown',
                'Community Center',
                'Member\'s Home'
            ]),
            'max_attendees' => $this->faker->optional(0.6)->numberBetween(5, 20),
            'created_by' => User::factory(),
        ];
    }

    public function past(): static
    {
        return $this->state(fn (array $attributes) => [
            'event_date' => $this->faker->dateTimeBetween('-90 days', 'yesterday')->format('Y-m-d'),
        ]);
    }

    public function upcoming(): static
    {
        return $this->state(fn (array $attributes) => [
            'event_date' => $this->faker->dateTimeBetween('tomorrow', '+60 days')->format('Y-m-d'),
        ]);
    }
}
