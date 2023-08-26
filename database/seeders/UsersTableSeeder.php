<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        for ($i = 1; $i <= 10; $i++) {
            DB::table('users')->insert([
                'email' => "user$i@gmail.com",
                'name' => "User $i",
                'password' => Hash::make('thao123'),
                'social_id' => null,
                'package_type' => 'Basic',
                'package_expiration_date' => Date::now()->addMonths(1),
                'max_storage' => 1000,
                'last_login_date' => null,
                'remember' => 0,
                'created_by' => null,
                'updated_by' => null,
                'updated_ts' => null,
                'created_at' => Date::now(),
                'updated_at' => Date::now(),
            ]);
        }
    }
}
