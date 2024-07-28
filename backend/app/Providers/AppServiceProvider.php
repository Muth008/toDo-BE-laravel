<?php

namespace App\Providers;

use App\Interfaces\TaskCategoryRepositoryInterface;
use App\Interfaces\TaskPriorityRepositoryInterface;
use App\Interfaces\TaskRepositoryInterface;
use App\Interfaces\TaskStatusRepositoryInterface;
use App\Repositories\TaskCategoryRepository;
use App\Repositories\TaskPriorityRepository;
use App\Repositories\TaskRepository;
use App\Repositories\TaskStatusRepository;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(
            TaskRepositoryInterface::class,
            TaskRepository::class
        );

        $this->app->bind(
            TaskCategoryRepositoryInterface::class,
            TaskCategoryRepository::class
        );

        $this->app->bind(
            TaskPriorityRepositoryInterface::class,
            TaskPriorityRepository::class
        );

        $this->app->bind(
            TaskStatusRepositoryInterface::class,
            TaskStatusRepository::class
        );
    }
}
