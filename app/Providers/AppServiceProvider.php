<?php

namespace App\Providers;

use App\Modules\User\File\FileModule;
use App\Modules\User\File\FileModuleInterface;
use App\Modules\User\Folder\FolderModule;
use App\Modules\User\Folder\FolderModuleInterface;
use App\Modules\User\UserModuleAbstract;
use App\Modules\User\UserModuleInterface;
use App\Modules\User\UserNormal;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserModuleInterface::class, UserNormal::class);
        $this->app->bind(FolderModuleInterface::class, FolderModule::class);
        $this->app->bind(FileModuleInterface::class, FileModule::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
