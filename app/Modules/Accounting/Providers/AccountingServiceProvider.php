<?php

namespace App\Modules\Accounting\Providers;

use App\Modules\Accounting\Repositories\Contracts\AccountingNatureRepositoryInterface;
use App\Modules\Accounting\Repositories\Contracts\AccountingEventRepositoryInterface;
use App\Modules\Accounting\Repositories\Contracts\AccountingMomentRepositoryInterface;
use App\Modules\Accounting\Repositories\Contracts\AccountClassRepositoryInterface;
use App\Modules\Accounting\Repositories\Contracts\AccountingStandardRepositoryInterface;
use App\Modules\Accounting\Repositories\Contracts\AccountingDocumentRepositoryInterface;
use App\Modules\Accounting\Repositories\Contracts\AccountingEntryHeaderRepositoryInterface;
use App\Modules\Accounting\Repositories\Contracts\AccountingEntryPositionRepositoryInterface;
use App\Modules\Accounting\Repositories\Contracts\AccountingAccountRepositoryInterface;
use App\Modules\Accounting\Repositories\Contracts\AccountingGroupRepositoryInterface;
use App\Modules\Accounting\Repositories\Contracts\BusinessStructureRepositoryInterface;
use App\Modules\Accounting\Repositories\Contracts\ChartAccountRepositoryInterface;
use App\Modules\Accounting\Repositories\Contracts\CostCenterClassRepositoryInterface;
use App\Modules\Accounting\Repositories\Contracts\CostCenterRepositoryInterface;
use App\Modules\Accounting\Repositories\Contracts\CostCenterNatureRepositoryInterface;
use App\Modules\Accounting\Repositories\Contracts\CostCenterTypeRepositoryInterface;
use App\Modules\Accounting\Repositories\Contracts\DocumentSourceTypeRepositoryInterface;
use App\Modules\Accounting\Repositories\Contracts\DocumentSourceRepositoryInterface;
use App\Modules\Accounting\Repositories\Contracts\ExerciseVariationRepositoryInterface;
use App\Modules\Accounting\Repositories\Contracts\FinancialStatementRepositoryInterface;
use App\Modules\Accounting\Repositories\Contracts\KeyOperationRepositoryInterface;
use App\Modules\Accounting\Repositories\Contracts\ModuleRepositoryInterface;
use App\Modules\Accounting\Repositories\Contracts\ReferenceRepositoryInterface;
use App\Modules\Accounting\Repositories\Contracts\SelectOptionRepositoryInterface;
use App\Modules\Accounting\Repositories\Contracts\TypeAccountRepositoryInterface;
use App\Modules\Accounting\Repositories\Contracts\TypePlanRepositoryInterface;
use App\Modules\Accounting\Repositories\EloquentAccountingNatureRepository;
use App\Modules\Accounting\Repositories\EloquentAccountingEventRepository;
use App\Modules\Accounting\Repositories\EloquentAccountingMomentRepository;
use App\Modules\Accounting\Repositories\EloquentAccountClassRepository;
use App\Modules\Accounting\Repositories\EloquentAccountingStandardRepository;
use App\Modules\Accounting\Repositories\EloquentAccountingDocumentRepository;
use App\Modules\Accounting\Repositories\EloquentAccountingEntryHeaderRepository;
use App\Modules\Accounting\Repositories\EloquentAccountingEntryPositionRepository;
use App\Modules\Accounting\Repositories\EloquentAccountingAccountRepository;
use App\Modules\Accounting\Repositories\EloquentAccountingGroupRepository;
use App\Modules\Accounting\Repositories\EloquentBusinessStructureRepository;
use App\Modules\Accounting\Repositories\EloquentChartAccountRepository;
use App\Modules\Accounting\Repositories\EloquentCostCenterClassRepository;
use App\Modules\Accounting\Repositories\EloquentCostCenterRepository;
use App\Modules\Accounting\Repositories\EloquentCostCenterNatureRepository;
use App\Modules\Accounting\Repositories\EloquentCostCenterTypeRepository;
use App\Modules\Accounting\Repositories\EloquentDocumentSourceTypeRepository;
use App\Modules\Accounting\Repositories\EloquentDocumentSourceRepository;
use App\Modules\Accounting\Repositories\EloquentExerciseVariationRepository;
use App\Modules\Accounting\Repositories\EloquentFinancialStatementRepository;
use App\Modules\Accounting\Repositories\EloquentKeyOperationRepository;
use App\Modules\Accounting\Repositories\EloquentModuleRepository;
use App\Modules\Accounting\Repositories\EloquentReferenceRepository;
use App\Modules\Accounting\Repositories\EloquentSelectOptionRepository;
use App\Modules\Accounting\Repositories\EloquentTypeAccountRepository;
use App\Modules\Accounting\Repositories\EloquentTypePlanRepository;
use Illuminate\Support\ServiceProvider;

class AccountingServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../Config/accounting.php', 'accounting');

        $this->app->bind(
            AccountingNatureRepositoryInterface::class,
            EloquentAccountingNatureRepository::class,
        );

        $this->app->bind(
            AccountingEventRepositoryInterface::class,
            EloquentAccountingEventRepository::class,
        );

        $this->app->bind(
            AccountingMomentRepositoryInterface::class,
            EloquentAccountingMomentRepository::class,
        );

        $this->app->bind(
            AccountClassRepositoryInterface::class,
            EloquentAccountClassRepository::class,
        );

        $this->app->bind(
            ExerciseVariationRepositoryInterface::class,
            EloquentExerciseVariationRepository::class,
        );

        $this->app->bind(
            AccountingStandardRepositoryInterface::class,
            EloquentAccountingStandardRepository::class,
        );

        $this->app->bind(
            AccountingDocumentRepositoryInterface::class,
            EloquentAccountingDocumentRepository::class,
        );

        $this->app->bind(
            AccountingEntryHeaderRepositoryInterface::class,
            EloquentAccountingEntryHeaderRepository::class,
        );

        $this->app->bind(
            AccountingEntryPositionRepositoryInterface::class,
            EloquentAccountingEntryPositionRepository::class,
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

        $this->app->bind(
            CostCenterClassRepositoryInterface::class,
            EloquentCostCenterClassRepository::class,
        );

        $this->app->bind(
            CostCenterRepositoryInterface::class,
            EloquentCostCenterRepository::class,
        );

        $this->app->bind(
            CostCenterNatureRepositoryInterface::class,
            EloquentCostCenterNatureRepository::class,
        );

        $this->app->bind(
            CostCenterTypeRepositoryInterface::class,
            EloquentCostCenterTypeRepository::class,
        );

        $this->app->bind(
            ModuleRepositoryInterface::class,
            EloquentModuleRepository::class,
        );

        $this->app->bind(
            DocumentSourceTypeRepositoryInterface::class,
            EloquentDocumentSourceTypeRepository::class,
        );

        $this->app->bind(
            DocumentSourceRepositoryInterface::class,
            EloquentDocumentSourceRepository::class,
        );

        $this->app->bind(
            FinancialStatementRepositoryInterface::class,
            EloquentFinancialStatementRepository::class,
        );

        $this->app->bind(
            KeyOperationRepositoryInterface::class,
            EloquentKeyOperationRepository::class,
        );

        $this->app->bind(
            ReferenceRepositoryInterface::class,
            EloquentReferenceRepository::class,
        );

        $this->app->bind(
            SelectOptionRepositoryInterface::class,
            EloquentSelectOptionRepository::class,
        );
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/api.php');
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }
}
