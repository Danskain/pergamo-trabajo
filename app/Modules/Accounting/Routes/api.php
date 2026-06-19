<?php

use App\Modules\Accounting\Http\Controllers\AccountingController;
use App\Modules\Accounting\Http\Controllers\AccountingAccountController;
use App\Modules\Accounting\Http\Controllers\AccountingEventController;
use App\Modules\Accounting\Http\Controllers\AccountingMomentController;
use App\Modules\Accounting\Http\Controllers\AccountingNatureController;
use App\Modules\Accounting\Http\Controllers\AccountClassController;
use App\Modules\Accounting\Http\Controllers\AccountingDocumentController;
use App\Modules\Accounting\Http\Controllers\AccountingEntryHeaderController;
use App\Modules\Accounting\Http\Controllers\AccountingEntryPositionController;
use App\Modules\Accounting\Http\Controllers\AccountingGroupController;
use App\Modules\Accounting\Http\Controllers\AccountingStandardController;
use App\Modules\Accounting\Http\Controllers\BusinessStructureController;
use App\Modules\Accounting\Http\Controllers\ChartAccountController;
use App\Modules\Accounting\Http\Controllers\CostCenterClassController;
use App\Modules\Accounting\Http\Controllers\CostCenterController;
use App\Modules\Accounting\Http\Controllers\CostCenterNatureController;
use App\Modules\Accounting\Http\Controllers\CostCenterTypeController;
use App\Modules\Accounting\Http\Controllers\DocumentSourceTypeController;
use App\Modules\Accounting\Http\Controllers\DocumentSourceController;
use App\Modules\Accounting\Http\Controllers\ExerciseVariationController;
use App\Modules\Accounting\Http\Controllers\FinancialStatementController;
use App\Modules\Accounting\Http\Controllers\KeyOperationController;
use App\Modules\Accounting\Http\Controllers\ModuleController;
use App\Modules\Accounting\Http\Controllers\ReferenceController;
use App\Modules\Accounting\Http\Controllers\TypeAccountController;
use App\Modules\Accounting\Http\Controllers\TypePlanController;
use Illuminate\Support\Facades\Route;

Route::prefix(config('accounting.route_prefix', 'api/accounting'))
    ->middleware(config('accounting.middleware', ['api']))
    ->name('accounting.')
    ->group(function (): void {
        Route::get('health', [AccountingController::class, 'health'])->name('health');
        Route::get('select-options/{catalog?}', [AccountingController::class, 'selectOptions'])->name('select-options');
        
        Route::get('accounting-documents', [AccountingDocumentController::class, 'index'])->name('accounting-documents.index');
        Route::post('accounting-documents', [AccountingDocumentController::class, 'store'])->name('accounting-documents.store');
        Route::get('accounting-documents/{accountingDocument}', [AccountingDocumentController::class, 'show'])->name('accounting-documents.show');
        Route::put('accounting-documents/{accountingDocument}', [AccountingDocumentController::class, 'update'])->name('accounting-documents.update');
        Route::delete('accounting-documents/{accountingDocument}', [AccountingDocumentController::class, 'destroy'])->name('accounting-documents.destroy');
        Route::post('accounting-documents/{accountingDocument}/restore', [AccountingDocumentController::class, 'restore'])->name('accounting-documents.restore');
        
        Route::get('accounting-entry-headers', [AccountingEntryHeaderController::class, 'index'])->name('accounting-entry-headers.index');
        Route::post('accounting-entry-headers', [AccountingEntryHeaderController::class, 'store'])->name('accounting-entry-headers.store');
        Route::get('accounting-entry-headers/{accountingEntryHeader}', [AccountingEntryHeaderController::class, 'show'])->name('accounting-entry-headers.show');
        Route::put('accounting-entry-headers/{accountingEntryHeader}', [AccountingEntryHeaderController::class, 'update'])->name('accounting-entry-headers.update');
        Route::delete('accounting-entry-headers/{accountingEntryHeader}', [AccountingEntryHeaderController::class, 'destroy'])->name('accounting-entry-headers.destroy');
        Route::post('accounting-entry-headers/{accountingEntryHeader}/restore', [AccountingEntryHeaderController::class, 'restore'])->name('accounting-entry-headers.restore');

        Route::get('accounting-entry-positions', [AccountingEntryPositionController::class, 'index'])->name('accounting-entry-positions.index');
        Route::post('accounting-entry-positions', [AccountingEntryPositionController::class, 'store'])->name('accounting-entry-positions.store');
        Route::get('accounting-entry-positions/{accountingEntryPosition}', [AccountingEntryPositionController::class, 'show'])->name('accounting-entry-positions.show');
        Route::put('accounting-entry-positions/{accountingEntryPosition}', [AccountingEntryPositionController::class, 'update'])->name('accounting-entry-positions.update');
        Route::delete('accounting-entry-positions/{accountingEntryPosition}', [AccountingEntryPositionController::class, 'destroy'])->name('accounting-entry-positions.destroy');
        Route::post('accounting-entry-positions/{accountingEntryPosition}/restore', [AccountingEntryPositionController::class, 'restore'])->name('accounting-entry-positions.restore');

        Route::get('documents-source', [DocumentSourceController::class, 'index'])->name('documents-source.index');
        Route::post('documents-source', [DocumentSourceController::class, 'store'])->name('documents-source.store');
        Route::get('documents-source/{documentSource}', [DocumentSourceController::class, 'show'])->name('documents-source.show');
        Route::put('documents-source/{documentSource}', [DocumentSourceController::class, 'update'])->name('documents-source.update');
        Route::delete('documents-source/{documentSource}', [DocumentSourceController::class, 'destroy'])->name('documents-source.destroy');
        Route::post('documents-source/{documentSource}/restore', [DocumentSourceController::class, 'restore'])->name('documents-source.restore');
        
        Route::get('exercise-variations', [ExerciseVariationController::class, 'index'])->name('exercise-variations.index');
        Route::post('exercise-variations', [ExerciseVariationController::class, 'store'])->name('exercise-variations.store');
        Route::get('exercise-variations/{exerciseVariation}', [ExerciseVariationController::class, 'show'])->name('exercise-variations.show');
        Route::put('exercise-variations/{exerciseVariation}', [ExerciseVariationController::class, 'update'])->name('exercise-variations.update');
        Route::delete('exercise-variations/{exerciseVariation}', [ExerciseVariationController::class, 'destroy'])->name('exercise-variations.destroy');
        Route::post('exercise-variations/{exerciseVariation}/restore', [ExerciseVariationController::class, 'restore'])->name('exercise-variations.restore');
        
        Route::get('accounting-standards', [AccountingStandardController::class, 'index'])->name('accounting-standards.index');
        Route::post('accounting-standards', [AccountingStandardController::class, 'store'])->name('accounting-standards.store');
        Route::get('accounting-standards/{accountingStandard}', [AccountingStandardController::class, 'show'])->name('accounting-standards.show');
        Route::put('accounting-standards/{accountingStandard}', [AccountingStandardController::class, 'update'])->name('accounting-standards.update');
        Route::delete('accounting-standards/{accountingStandard}', [AccountingStandardController::class, 'destroy'])->name('accounting-standards.destroy');
        Route::post('accounting-standards/{accountingStandard}/restore', [AccountingStandardController::class, 'restore'])->name('accounting-standards.restore');

        Route::get('accounting-natures', [AccountingNatureController::class, 'index'])->name('accounting-natures.index');
        Route::post('accounting-natures', [AccountingNatureController::class, 'store'])->name('accounting-natures.store');
        Route::get('accounting-natures/{accountingNature}', [AccountingNatureController::class, 'show'])->name('accounting-natures.show');
        Route::put('accounting-natures/{accountingNature}', [AccountingNatureController::class, 'update'])->name('accounting-natures.update');
        Route::delete('accounting-natures/{accountingNature}', [AccountingNatureController::class, 'destroy'])->name('accounting-natures.destroy');
        Route::post('accounting-natures/{accountingNature}/restore', [AccountingNatureController::class, 'restore'])->name('accounting-natures.restore');

        Route::get('accounting-moments', [AccountingMomentController::class, 'index'])->name('accounting-moments.index');
        Route::post('accounting-moments', [AccountingMomentController::class, 'store'])->name('accounting-moments.store');
        Route::get('accounting-moments/{accountingMoment}', [AccountingMomentController::class, 'show'])->name('accounting-moments.show');
        Route::put('accounting-moments/{accountingMoment}', [AccountingMomentController::class, 'update'])->name('accounting-moments.update');
        Route::delete('accounting-moments/{accountingMoment}', [AccountingMomentController::class, 'destroy'])->name('accounting-moments.destroy');
        Route::post('accounting-moments/{accountingMoment}/restore', [AccountingMomentController::class, 'restore'])->name('accounting-moments.restore');

        Route::get('accounting-events', [AccountingEventController::class, 'index'])->name('accounting-events.index');
        Route::post('accounting-events', [AccountingEventController::class, 'store'])->name('accounting-events.store');
        Route::get('accounting-events/{accountingEvent}', [AccountingEventController::class, 'show'])->name('accounting-events.show');
        Route::put('accounting-events/{accountingEvent}', [AccountingEventController::class, 'update'])->name('accounting-events.update');
        Route::delete('accounting-events/{accountingEvent}', [AccountingEventController::class, 'destroy'])->name('accounting-events.destroy');
        Route::post('accounting-events/{accountingEvent}/restore', [AccountingEventController::class, 'restore'])->name('accounting-events.restore');

        Route::get('account-classes', [AccountClassController::class, 'index'])->name('account-classes.index');
        Route::post('account-classes', [AccountClassController::class, 'store'])->name('account-classes.store');
        Route::get('account-classes/{accountClass}', [AccountClassController::class, 'show'])->name('account-classes.show');
        Route::put('account-classes/{accountClass}', [AccountClassController::class, 'update'])->name('account-classes.update');
        Route::delete('account-classes/{accountClass}', [AccountClassController::class, 'destroy'])->name('account-classes.destroy');
        Route::post('account-classes/{accountClass}/restore', [AccountClassController::class, 'restore'])->name('account-classes.restore');

        Route::get('accounting-groups', [AccountingGroupController::class, 'index'])->name('accounting-groups.index');
        Route::post('accounting-groups', [AccountingGroupController::class, 'store'])->name('accounting-groups.store');
        Route::get('accounting-groups/{accountingGroup}', [AccountingGroupController::class, 'show'])->name('accounting-groups.show');
        Route::put('accounting-groups/{accountingGroup}', [AccountingGroupController::class, 'update'])->name('accounting-groups.update');
        Route::delete('accounting-groups/{accountingGroup}', [AccountingGroupController::class, 'destroy'])->name('accounting-groups.destroy');
        Route::post('accounting-groups/{accountingGroup}/restore', [AccountingGroupController::class, 'restore'])->name('accounting-groups.restore');

        Route::get('types-accounts', [TypeAccountController::class, 'index'])->name('types-accounts.index');
        Route::post('types-accounts', [TypeAccountController::class, 'store'])->name('types-accounts.store');
        Route::get('types-accounts/{typeAccount}', [TypeAccountController::class, 'show'])->name('types-accounts.show');
        Route::put('types-accounts/{typeAccount}', [TypeAccountController::class, 'update'])->name('types-accounts.update');
        Route::delete('types-accounts/{typeAccount}', [TypeAccountController::class, 'destroy'])->name('types-accounts.destroy');
        Route::post('types-accounts/{typeAccount}/restore', [TypeAccountController::class, 'restore'])->name('types-accounts.restore');
        
        Route::get('types-plans', [TypePlanController::class, 'index'])->name('types-plans.index');
        Route::post('types-plans', [TypePlanController::class, 'store'])->name('types-plans.store');
        Route::get('types-plans/{typePlan}', [TypePlanController::class, 'show'])->name('types-plans.show');
        Route::put('types-plans/{typePlan}', [TypePlanController::class, 'update'])->name('types-plans.update');
        Route::delete('types-plans/{typePlan}', [TypePlanController::class, 'destroy'])->name('types-plans.destroy');
        Route::post('types-plans/{typePlan}/restore', [TypePlanController::class, 'restore'])->name('types-plans.restore');

        Route::get('chart-accounts', [ChartAccountController::class, 'index'])->name('chart-accounts.index');
        Route::post('chart-accounts', [ChartAccountController::class, 'store'])->name('chart-accounts.store');
        Route::get('chart-accounts/{chartAccount}', [ChartAccountController::class, 'show'])->name('chart-accounts.show');
        Route::put('chart-accounts/{chartAccount}', [ChartAccountController::class, 'update'])->name('chart-accounts.update');
        Route::delete('chart-accounts/{chartAccount}', [ChartAccountController::class, 'destroy'])->name('chart-accounts.destroy');
        Route::post('chart-accounts/{chartAccount}/restore', [ChartAccountController::class, 'restore'])->name('chart-accounts.restore');
        
        Route::get('cost-center-classes', [CostCenterClassController::class, 'index'])->name('cost-center-classes.index');
        Route::post('cost-center-classes', [CostCenterClassController::class, 'store'])->name('cost-center-classes.store');
        Route::get('cost-center-classes/{costCenterClass}', [CostCenterClassController::class, 'show'])->name('cost-center-classes.show');
        Route::put('cost-center-classes/{costCenterClass}', [CostCenterClassController::class, 'update'])->name('cost-center-classes.update');
        Route::delete('cost-center-classes/{costCenterClass}', [CostCenterClassController::class, 'destroy'])->name('cost-center-classes.destroy');
        Route::post('cost-center-classes/{costCenterClass}/restore', [CostCenterClassController::class, 'restore'])->name('cost-center-classes.restore');

        Route::get('cost-centers', [CostCenterController::class, 'index'])->name('cost-centers.index');
        Route::post('cost-centers', [CostCenterController::class, 'store'])->name('cost-centers.store');
        Route::get('cost-centers/{costCenter}', [CostCenterController::class, 'show'])->name('cost-centers.show');
        Route::put('cost-centers/{costCenter}', [CostCenterController::class, 'update'])->name('cost-centers.update');
        Route::delete('cost-centers/{costCenter}', [CostCenterController::class, 'destroy'])->name('cost-centers.destroy');
        Route::post('cost-centers/{costCenter}/restore', [CostCenterController::class, 'restore'])->name('cost-centers.restore');

        Route::get('cost-center-natures', [CostCenterNatureController::class, 'index'])->name('cost-center-natures.index');
        Route::post('cost-center-natures', [CostCenterNatureController::class, 'store'])->name('cost-center-natures.store');
        Route::get('cost-center-natures/{costCenterNature}', [CostCenterNatureController::class, 'show'])->name('cost-center-natures.show');
        Route::put('cost-center-natures/{costCenterNature}', [CostCenterNatureController::class, 'update'])->name('cost-center-natures.update');
        Route::delete('cost-center-natures/{costCenterNature}', [CostCenterNatureController::class, 'destroy'])->name('cost-center-natures.destroy');
        Route::post('cost-center-natures/{costCenterNature}/restore', [CostCenterNatureController::class, 'restore'])->name('cost-center-natures.restore');
        
        Route::get('cost-center-types', [CostCenterTypeController::class, 'index'])->name('cost-center-types.index');
        Route::post('cost-center-types', [CostCenterTypeController::class, 'store'])->name('cost-center-types.store');
        Route::get('cost-center-types/{costCenterType}', [CostCenterTypeController::class, 'show'])->name('cost-center-types.show');
        Route::put('cost-center-types/{costCenterType}', [CostCenterTypeController::class, 'update'])->name('cost-center-types.update');
        Route::delete('cost-center-types/{costCenterType}', [CostCenterTypeController::class, 'destroy'])->name('cost-center-types.destroy');
        Route::post('cost-center-types/{costCenterType}/restore', [CostCenterTypeController::class, 'restore'])->name('cost-center-types.restore');

        Route::get('accounting-accounts', [AccountingAccountController::class, 'index'])->name('accounting-accounts.index');
        Route::post('accounting-accounts', [AccountingAccountController::class, 'store'])->name('accounting-accounts.store');
        Route::get('accounting-accounts/{accountingAccount}', [AccountingAccountController::class, 'show'])->name('accounting-accounts.show');
        Route::put('accounting-accounts/{accountingAccount}', [AccountingAccountController::class, 'update'])->name('accounting-accounts.update');
        Route::delete('accounting-accounts/{accountingAccount}', [AccountingAccountController::class, 'destroy'])->name('accounting-accounts.destroy');
        Route::post('accounting-accounts/{accountingAccount}/restore', [AccountingAccountController::class, 'restore'])->name('accounting-accounts.restore');

        Route::get('business-structures', [BusinessStructureController::class, 'index'])->name('business-structures.index');
        Route::post('business-structures', [BusinessStructureController::class, 'store'])->name('business-structures.store');
        Route::get('business-structures/{businessStructure}', [BusinessStructureController::class, 'show'])->name('business-structures.show');
        Route::put('business-structures/{businessStructure}', [BusinessStructureController::class, 'update'])->name('business-structures.update');
        Route::delete('business-structures/{businessStructure}', [BusinessStructureController::class, 'destroy'])->name('business-structures.destroy');
        Route::post('business-structures/{businessStructure}/restore', [BusinessStructureController::class, 'restore'])->name('business-structures.restore');

        Route::get('modules', [ModuleController::class, 'index'])->name('modules.index');
        Route::post('modules', [ModuleController::class, 'store'])->name('modules.store');
        Route::get('modules/{module}', [ModuleController::class, 'show'])->name('modules.show');
        Route::put('modules/{module}', [ModuleController::class, 'update'])->name('modules.update');
        Route::delete('modules/{module}', [ModuleController::class, 'destroy'])->name('modules.destroy');
        Route::post('modules/{module}/restore', [ModuleController::class, 'restore'])->name('modules.restore');

        Route::get('document-source-types', [DocumentSourceTypeController::class, 'index'])->name('document-source-types.index');
        Route::post('document-source-types', [DocumentSourceTypeController::class, 'store'])->name('document-source-types.store');
        Route::get('document-source-types/{documentSourceType}', [DocumentSourceTypeController::class, 'show'])->name('document-source-types.show');
        Route::put('document-source-types/{documentSourceType}', [DocumentSourceTypeController::class, 'update'])->name('document-source-types.update');
        Route::delete('document-source-types/{documentSourceType}', [DocumentSourceTypeController::class, 'destroy'])->name('document-source-types.destroy');
        Route::post('document-source-types/{documentSourceType}/restore', [DocumentSourceTypeController::class, 'restore'])->name('document-source-types.restore');

        Route::get('financial-statements', [FinancialStatementController::class, 'index'])->name('financial-statements.index');
        Route::post('financial-statements', [FinancialStatementController::class, 'store'])->name('financial-statements.store');
        Route::get('financial-statements/{financialStatement}', [FinancialStatementController::class, 'show'])->name('financial-statements.show');
        Route::put('financial-statements/{financialStatement}', [FinancialStatementController::class, 'update'])->name('financial-statements.update');
        Route::delete('financial-statements/{financialStatement}', [FinancialStatementController::class, 'destroy'])->name('financial-statements.destroy');
        Route::post('financial-statements/{financialStatement}/restore', [FinancialStatementController::class, 'restore'])->name('financial-statements.restore');

        Route::get('key-operations', [KeyOperationController::class, 'index'])->name('key-operations.index');
        Route::post('key-operations', [KeyOperationController::class, 'store'])->name('key-operations.store');
        Route::get('key-operations/{keyOperation}', [KeyOperationController::class, 'show'])->name('key-operations.show');
        Route::put('key-operations/{keyOperation}', [KeyOperationController::class, 'update'])->name('key-operations.update');
        Route::delete('key-operations/{keyOperation}', [KeyOperationController::class, 'destroy'])->name('key-operations.destroy');
        Route::post('key-operations/{keyOperation}/restore', [KeyOperationController::class, 'restore'])->name('key-operations.restore');

        Route::get('references', [ReferenceController::class, 'index'])->name('references.index');
        Route::post('references', [ReferenceController::class, 'store'])->name('references.store');
        Route::get('references/{reference}', [ReferenceController::class, 'show'])->name('references.show');
        Route::put('references/{reference}', [ReferenceController::class, 'update'])->name('references.update');
        Route::delete('references/{reference}', [ReferenceController::class, 'destroy'])->name('references.destroy');
        Route::post('references/{reference}/restore', [ReferenceController::class, 'restore'])->name('references.restore');
    });
