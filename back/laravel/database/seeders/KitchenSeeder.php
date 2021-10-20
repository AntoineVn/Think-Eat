<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class KitchenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $min = strtotime(20210401);
        $max = strtotime(20210431);
        $val = rand($min, $max);
        DB::table('kitchen')->insert([
            'name' => Str::random(10),
            'expire_date' => date('Y-m-d H:i:s', $val),
            'status' => rand(0, 3),
            'quantity' => rand(1, 10),
            'price' => rand(1, 999),
            'category_id' => rand(1, 7),
            'user_id' => rand(1, 5),
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s")
        ]);
    }
}
