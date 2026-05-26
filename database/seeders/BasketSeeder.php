<?php

namespace Database\Seeders;

use App\Models\Basket;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BasketSeeder extends Seeder
{
    public function run(): void
    {
        User:: all()->each(function ($user) {

            Basket::factory()
                ->count(1)
                ->create([
                    'user_id'=>$user->id
                ]);
        });
    }
}
