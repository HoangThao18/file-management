<?php

namespace App\Jobs;

use App\Models\File;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class uploadFileToCloud implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected File $file)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $model = $this->file;

        if (!$model->uploaded_on_cloud) {
            $localPath = Storage::disk("local")->path($model->path);
            Log::debug("uploading file " . $localPath);
            try {
                $success = Storage::put($model->path, Storage::disk('local')->get($model->path));
                if ($success) {
                    Log::debug("uploaded");
                    $model->uploaded_on_cloud = 1;
                    $model->save();
                    Storage::disk('local')->delete($model->path);
                } else {
                    Log::error("fail to upload on s3");
                }
            } catch (\Exception $e) {
                Log::error($e->getMessage());
            }
        }
    }
}
