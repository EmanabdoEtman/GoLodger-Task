<?php

namespace Database\Factories;

 
use App\Models\Product;
use App\Models\ProductTranslation;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductTranslationFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProductTranslation::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {          
           
        return [ 
            'product_id' => Product::factory(), 
            'product_name' => $this->faker->numberBetween(0,100),
            'locale' => $this->faker->randomElement(['ar','en']),
          
        ];
    }
}
