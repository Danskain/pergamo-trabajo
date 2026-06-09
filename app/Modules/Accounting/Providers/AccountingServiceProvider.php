<?php

namespace App\Modules\Accounting\Providers;

use App\Modules\Accounting\Repositories\Contracts\AccountingStandardRepositoryInterface;
use App\Modules\Accounting\Repositories\Contracts\ExerciseVariationRepositoryInterface;
use App\Modules\Accounting\Repositories\Contracts\TypePlanRepositoryInterface;
use App\Modules\Accounting\Repositories\EloquentAccountingStandardRepository;
use App\Modules\Accounting\Repositories\EloquentExerciseVariationRepository;
use App\Modules\Accounting\Repositories\EloquentTypePlanRepository;
use Illuminate\Support\ServiceProvider;

class AccountingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../Config/accounting.php', 'accounting');

        $this->app->bind(
            ExerciseVariationRepositoryInterface::class,
            EloquentExerciseVariationRepository::class,
        );

        $this->app->bind(
            AccountingStandardRepositoryInterface::class,
            EloquentAccountingStandardRepository::class,
        );

        $this->app->bind(
            TypePlanRepositoryInterface::class,
            EloquentTypePlanRepository::class,
        );
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/api.php');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }
}
