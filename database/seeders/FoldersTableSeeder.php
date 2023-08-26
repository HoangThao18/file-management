<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FoldersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        for ($i = 1; $i <= 10; $i++) {
            DB::table('folders')->insert([
                'name' => "Folder $i",
                'size' => 100 + $i,
                'path' => "/path/to/folder$i",
                'user_id' => $i,
                'created_by' => "User $i",
                'updated_by' => "User $i",
                'created_at' => now(),
                'updated_at' => now(),
                'updated_ts' => now(),
            ]);
        }
    }
}
