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
        $groupNames = [
            'Mystery Lovers Circle',
            'Sci-Fi Enthusiasts',
            'Classic Literature Club',
            'Contemporary Fiction Readers',
            'Fantasy Book Guild',
            'Romance Book Club',
            'Thriller & Suspense Group',
            'Historical Fiction Society',
            'Young Adult Book Lovers',
            'Non-Fiction Discussion Group',
            'Poetry Appreciation Circle',
            'Manga & Graphic Novel Club',
            'Book to Movie Adaptations',
            'Self-Help & Personal Growth',
            'Business & Finance Readers',
            'Philosophy & Ideas Forum',
            'Travel & Adventure Books',
            'Horror Story Circle',
            'Biography & Memoir Club',
            'Local Authors Support Group',
        ];

        $descriptions = [
            'A welcoming community for book lovers to share thoughts and recommendations.',
            'Join us for engaging discussions about our favorite reads!',
            'Monthly meetings and lively debates about literature.',
            'Discover new authors and genres with fellow reading enthusiasts.',
            'A safe space to explore diverse perspectives through books.',
            'Connect with readers who share your passion for great stories.',
            'Weekly virtual meetups to discuss current reads and classics.',
            'Building friendships one book at a time.',
            'From bestsellers to hidden gems, we read it all!',
            'Challenging ourselves with thought-provoking literature.',
        ];

        return [
            'name' => $this->faker->unique()->randomElement($groupNames),
            'description' => $this->faker->randomElement($descriptions),
            'owner_id' => User::inRandomOrder()->first()->id ?? User::factory(),
            'max_members' => $this->faker->randomElement([10, 15, 20, 25, 30]),
            'is_private' => $this->faker->boolean(30), // 30% chance of being private
            'status' => $this->faker->randomElement(['active', 'active', 'active', 'inactive']), // 75% active
            'created_at' => $this->faker->dateTimeBetween('-6 months', 'now'),
        ];
    }

    public function private(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_private' => true,
        ]);
    }

    public function public(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_private' => false,
        ]);
    }
}

