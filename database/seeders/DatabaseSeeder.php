<?php

namespace Database\Seeders;

use App\Models\Shopkeeper;
use App\Models\Transactions\Wallet;
use App\Models\User;
use Illuminate\Database\Seeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory(10)->create();
        Shopkeeper::factory(1)->create();
        Wallet::factory(10)->create();
    }
}
