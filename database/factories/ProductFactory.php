<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'=>$this->faker->title(),
            'description'=>$this->faker->text(),
            'img_path'=>'https://cdn.new-brz.net/public/images/articles/az/0/ghlGb9ipu7c7H1wlRlkMMhR6R27v4IXZUTzRW3hb.jpg',
            'price'=>rand(1000, 1000000),
            'discount_price'=>rand(1000, 1000000),
            'price_from'=>rand(1000, 1000000),
            'stock_quantity' => rand(0, 100)
        ];
    }
}
