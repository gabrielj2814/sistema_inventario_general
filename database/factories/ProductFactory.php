<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Product;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'description' => $this->faker->text(),
            'barcode' => $this->faker->regexify('[A-Za-z0-9]{255}'),
            'stock' => $this->faker->numberBetween(-10000, 10000),
            'price_unit' => $this->faker->randomFloat(2, 0, 999999.99),
            'unit_of_measurement' => $this->faker->randomElement(["mg","g","kg","t","ml","l","cm3","m3","mm","cm","m","km","ud","dz","pkg","box"]),
            'category_id' => Category::factory(),
        ];
    }
}
