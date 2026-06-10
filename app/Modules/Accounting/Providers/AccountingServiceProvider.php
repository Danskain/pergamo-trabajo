<?php

namespace App\Modules\Accounting\Providers;

use App\Modules\Accounting\Repositories\Contracts\AccountingStandardRepositoryInterface;
use App\Modules\Accounting\Repositories\Contracts\AccountingAccountRepositoryInterface;
use App\Modules\Accounting\Repositories\Contracts\AccountingGroupRepositoryInterface;
use App\Modules\Accounting\Repositories\Contracts\BusinessStructureRepositoryInterface;
use App\Modules\Accounting\Repositories\Contracts\ChartAccountRepositoryInterface;
use App\Modules\Accounting\Repositories\Contracts\ExerciseVariationRepositoryInterface;
use App\Modules\Accounting\Repositories\Contracts\TypeAccountRepositoryInterface;
use App\Modules\Accounting\Repositories\Contracts\TypePlanRepositoryInterface;
use App\Modules\Accounting\Repositories\EloquentAccountingStandardRepository;
use App\Modules\Accounting\Repositories\EloquentAccountingAccountRepository;
use App\Modules\Accounting\Repositories\EloquentAccountingGroupRepository;
use App\Modules\Accounting\Repositories\EloquentBusinessStructureRepository;
use App\Modules\Accounting\Repositories\EloquentChartAccountRepository;
use App\Modules\Accounting\Repositories\EloquentExerciseVariationRepository;
use App\Modules\Accounting\Repositories\EloquentTypeAccountRepository;
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
            AccountingAccountRepositoryInterface::class,
            EloquentAccountingAccountRepository::class,
        );

        $this->app->bind(
            BusinessStructureRepositoryInterface::class,
            EloquentBusinessStructureRepository::class,
        );

        $this->app->bind(
            AccountingGroupRepositoryInterface::class,
            EloquentAccountingGroupRepository::class,
        );

        $this->app->bind(
            TypeAccountRepositoryInterface::class,
            EloquentTypeAccountRepository::class,
        );

        $this->app->bind(
            TypePlanRepositoryInterface::class,
            EloquentTypePlanRepository::class,
        );

        $this->app->bind(
            ChartAccountRepositoryInterface::class,
            EloquentChartAccountRepository::class,
        );
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/api.php');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }
}
