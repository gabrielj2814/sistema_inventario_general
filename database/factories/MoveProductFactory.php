<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\MoveProduct;
use App\Models\Product;
use App\Models\User;
use App\Models\Warehouse;

class MoveProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = MoveProduct::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'from_warehouse' => Warehouse::factory(),
            'until_warehouse' => Warehouse::factory(),
            'amount' => $this->faker->numberBetween(-10000, 10000),
            'user_id' => User::factory(),
            'from_warehouse_id' => Warehouse::factory(),
            'until_warehouse_id' => Warehouse::factory(),
        ];
    }
}
