<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

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
        return 
        [
            'id' => $this->faker->uuid,
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'document_id' => rand(11111111,99999999),
            'password' => $this->hasher->make('ricardo1998')
        ];
    }
}
