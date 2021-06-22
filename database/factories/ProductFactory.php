<?php

namespace Database\Factories;


use App\Models\Vendor;
use App\Models\Product; 
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

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
     *
     * @return array
     */
    public function definition()
    {          
           
        return [ 
            'vendor_id' => Vendor::factory(), 
            'orders_count' => $this->faker->numberBetween(0,100),
            'votes' => $this->faker->numberBetween(0,100),
            'price' => $this->faker->numberBetween(100,1000),
          
        ];
    }
}
