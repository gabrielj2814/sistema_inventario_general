<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\InventoryMovement;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductSupplier;
use App\Models\User;
use App\Models\Warehouse;

class InventoryMovementFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = InventoryMovement::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'date_movement' => $this->faker->date(),
            'type' => $this->faker->randomElement(["entrada","salida","ajuste"]),
            'amount' => $this->faker->numberBetween(-10000, 10000),
            'note' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'order_id' => Order::factory(),
            'product_supplier_id' => ProductSupplier::factory(),
            'user_id' => User::factory(),
            'warehouse_id' => Warehouse::factory(),
            'product_id' => Product::factory(),
        ];
    }
}
