<?php

namespace Database\Factories;

use App\Models\Exchange;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Exchange>
 */
class ExchangeFactory extends Factory
{
    protected $model = Exchange::class;

    public function definition()
    {
        return [
            'type' => $this->faker->randomElement(['RESERVATION', 'ECHANGE']),
            'status' => $this->faker->randomElement(['EN_ATTENTE', 'EN_COURS', 'TERMINE', 'ANNULE']),
            'dateDebut' => $this->faker->date(),
            'dateFin' => $this->faker->dateTimeBetween('+1 day', '+30 days')->format('Y-m-d'),
            'userInitiateurId' => \App\Models\User::factory(),
            'userRecepteurId' => \App\Models\User::factory(),
            'bookDemandeId' => \App\Models\Book::factory(),
            'bookOffertId' => null,
        ];
    }
}