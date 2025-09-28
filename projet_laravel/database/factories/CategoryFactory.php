<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Category::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categories = [
            'Programmation' => 'Livres sur les langages de programmation et le développement',
            'Fiction' => 'Romans, nouvelles et littérature imaginaire',
            'Science' => 'Livres scientifiques et de vulgarisation',
            'Business' => 'Livres sur le business, management et entrepreneuriat',
            'Histoire' => 'Livres d\'histoire et biographies',
            'Art' => 'Livres sur l\'art, la peinture et la créativité',
            'Cuisine' => 'Livres de recettes et art culinaire',
            'Voyage' => 'Guides de voyage et récits d\'aventure',
            'Psychologie' => 'Livres sur la psychologie et développement personnel',
            'Philosophie' => 'Ouvrages philosophiques et de réflexion',
        ];

        $categoryName = $this->faker->randomElement(array_keys($categories));
        
        return [
            'name' => $categoryName,
            'description' => $categories[$categoryName],
            'age_allowed' => $this->faker->randomElement(['Tout âge', '12+', '16+', '18+']),
            'color' => $this->faker->hexColor(),
            'icon' => $this->faker->randomElement(['📚', '🎨', '🔬', '💼', '🏛️', '🎭', '🍳', '✈️', '🧠', '💭']),
            'is_featured' => $this->faker->boolean(30), // 30% chance of being featured
            'sort_order' => $this->faker->numberBetween(0, 100),
            'reading_tips' => $this->faker->sentence(),
            'popular_authors' => json_encode($this->faker->words(3)),
            'is_active' => true,
        ];
    }
}