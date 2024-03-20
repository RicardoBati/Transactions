<?php

namespace Database\Factories\Transactions;

use App\Models\Shopkeeper;
use App\Models\Transactions\Wallet;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class WalletFactory extends Factory
{
    protected $model = Wallet::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $shopkeeper = Shopkeeper::factory()->create();
        $user = User::factory()->create();

        return [
            'id' => $this->faker->uuid,
            'user_id' => $this->faker->randomElement([$shopkeeper->id, $user->id]),
            'balance' => "50000"
        ];
    }
}
