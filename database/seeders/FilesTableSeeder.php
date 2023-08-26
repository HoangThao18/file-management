<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FilesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        for ($i = 1; $i <= 10; $i++) {
            DB::table('files')->insert([
                'name' => "File $i",
                'size' => 50 + $i,
                'path' => "/path/to/file$i.txt",
                'description' => "Description for File $i",
                'status' => 0,
                'link_share' => "https://example.com/file$i",
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
