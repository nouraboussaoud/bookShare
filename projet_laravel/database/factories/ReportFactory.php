<?php

namespace Database\Factories;

use App\Models\Report;
use App\Models\User;
use App\Models\Exchange;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Report>
 */
class ReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $types = [Report::TYPE_CONFLIT_ECHANGE, Report::TYPE_COMPORTEMENT];
        $statuses = [Report::STATUS_EN_ATTENTE, Report::STATUS_TRAITE, Report::STATUS_REJETE];

        return [
            'type' => $this->faker->randomElement($types),
            'description' => $this->faker->paragraph(3),
            'status' => $this->faker->randomElement($statuses),
            'reporter_id' => User::factory(),
            'reported_user_id' => User::factory(),
            'exchange_id' => null, // Will be set in specific states if needed
        ];
    }

    /**
     * Indicate that the report is for exchange conflict.
     */
    public function exchangeConflict(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => Report::TYPE_CONFLIT_ECHANGE,
            'exchange_id' => Exchange::factory(),
            'reported_user_id' => null,
        ]);
    }

    /**
     * Indicate that the report is for inappropriate behavior.
     */
    public function behavior(): static
    {
        return $this->state(fn (array $attributes) => [
            'type' => Report::TYPE_COMPORTEMENT,
            'exchange_id' => null,
        ]);
    }

    /**
     * Indicate that the report is pending.
     */
    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Report::STATUS_EN_ATTENTE,
        ]);
    }

    /**
     * Indicate that the report is processed.
     */
    public function processed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Report::STATUS_TRAITE,
        ]);
    }

    /**
     * Indicate that the report is rejected.
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => Report::STATUS_REJETE,
        ]);
    }
}
