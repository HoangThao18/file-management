<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TrashTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        for ($i = 1; $i <= 10; $i++) {
            DB::table('trash')->insert([
                'file_id' => $i,
                'folder_id' => $i,
                'created_by' => "User $i",
                'updated_by' => "User $i",
                'created_at' => now(),
                'updated_at' => now(),
                'updated_Ts' => now(),
            ]);
        }
    }
}
