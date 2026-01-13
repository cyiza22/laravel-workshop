<?php

namespace Database\Seeders;
use App\Models\Product;
use App\Models\Order;
use App\Models\User;


// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        
        // Product::factory(10)->create();
        // Order::factory(10)->create();
        
        $users = User::all();

        foreach ($users as $user) {
            Order::factory(10)->create([
                'user_id' => $user->id,
            ]);

            Product::factory(10)->create([
                'user_id' => $user->id,
            ]);
            }
        }
}
