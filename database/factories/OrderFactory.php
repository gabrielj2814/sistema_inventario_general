<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Customer;
use App\Models\Order;
use App\Models\User;

class OrderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Order::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'dateOrder' => $this->faker->date(),
            'total' => $this->faker->numberBetween(-10000, 10000),
            'statu' => $this->faker->randomElement(["pendiente","completado","cancelado"]),
            'user_id' => User::factory(),
            'customer_id' => Customer::factory(),
        ];
    }
}
