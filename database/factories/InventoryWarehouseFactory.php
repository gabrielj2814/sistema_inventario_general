<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\InventoryWarehouse;
use App\Models\Product;
use App\Models\Warehouse;

class InventoryWarehouseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InventoryWarehouse::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'stock' => $this->faker->numberBetween(-10000, 10000),
            'warehouse_id' => Warehouse::factory(),
            'product_id' => Product::factory(),
        ];
    }
}
