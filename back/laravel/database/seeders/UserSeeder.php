<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('users')->insert([
            'firstname' => Str::random(10),
            'lastname' => Str::random(10),
            'nickname' => Str::random(10),
            'email' => Str::random(10).'@gmail.com',
            'phone' => random_int(0000000000, 9999999999), 
            'location' => "Lyon",
            'password' => Hash::make('password'),
            'created_at' => date("Y-m-d H:i:s"),
            'updated_at' => date("Y-m-d H:i:s"),
        ]);
    }
}
