<?php

namespace Database\Factories;

use App\Models\Shopkeeper;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;

class ShopkeeperFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Shopkeeper::class;

    protected $hasher;


    public function __construct($hasher = null)
    {
        parent::__construct();

        $this->hasher = $hasher ?: app(HasherContract::class);
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'id' => $this->faker->uuid,
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'document_id' => rand(55555555,88888888),
            'password' => $this->hasher->make('ricardo1998')
        ];
    }
}
