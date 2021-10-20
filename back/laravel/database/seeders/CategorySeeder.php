<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        \DB::table('categories')->delete();

        \DB::table('categories')->insert(array (
            0=>
                array (
                    'id' => 1,
                    'name' => 'Meat, fish and eggs',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),
                ),
            1=>
                array (
                    'id' => 2,
                    'name' => 'Milk products',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),
                ),
            2=>
                array (
                    'id' => 3,
                    'name' => 'Cooking oils and fats',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),
                ),
            3=>
                array (
                    'id' => 4,
                    'name' => 'Vegetables, legumes and fruits',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),
                ),
            4=>
                array (
                    'id' => 5,
                    'name' => 'Cereals, cereal-based products and grains',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),
                ),
            5=>
                array (
                    'id' => 6,
                    'name' => 'Sugar and sweet products',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),
                ),
            6=>
                array (
                    'id' => 7,
                    'name' => 'Beverages',
                    'created_at' => date("Y-m-d H:i:s"),
                    'updated_at' => date("Y-m-d H:i:s"),
                ),            
        ));
    }
}
