<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\TravelOrders;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TravelOrders>
 */
class TravelOrdersFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected $model = TravelOrders::class;

    public function definition(): array
    {
        return [
            'customer_name' => $this->faker->customer_name(),
            'destiny' => $this->faker->destiny(),
            'start_date' => $this->faker->start_date(),
            'return_date' => $this->faker->return_date(),
            'status' => $this->faker->status()
        ];
    }
}
