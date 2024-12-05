<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;

class OrderDetailFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OrderDetail::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'amount' => $this->faker->numberBetween(-10000, 10000),
            'price_unit' => $this->faker->word(),
            'subTotal' => $this->faker->numberBetween(-10000, 10000),
            'product_id' => Product::factory(),
            'order_id' => Order::factory(),
        ];
    }
}
