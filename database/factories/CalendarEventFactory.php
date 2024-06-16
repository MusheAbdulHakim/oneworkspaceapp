<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CalendarEvent>
 */
class CalendarEventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id,
            'title' => $this->faker->realText(30),
            'startDate' => $this->faker->dateTimeBetween('-2 months'),
            'endDate' => $this->faker->dateTimeBetween('-1 months'),
            'url' => $this->faker->url(),
            'color' => $this->faker->hexColor(),
            'description' => $this->faker->realText(),
            'data' => null
        ];
    }
}
