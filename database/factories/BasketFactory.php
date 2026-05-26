<?php

namespace Database\Factories;

use App\Models\Basket;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Basket>
 */
class BasketFactory extends Factory
{
    protected $model = Basket::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'status' => $this->faker->randomElement(['null', 'pending', 'processing', 'completed']),
        ];
    }
}
