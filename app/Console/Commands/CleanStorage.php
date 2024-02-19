<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanStorage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:clean-storage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $zips = Storage::disk('local')->files('public/zip');
        $temps = Storage::disk('local')->files('public/temp');

        foreach ($zips as $zip) {
            Storage::disk('local')->delete($zip);
        }
        foreach ($temps as $temp) {
            Storage::disk('local')->delete($temp);
        }
    }
}
