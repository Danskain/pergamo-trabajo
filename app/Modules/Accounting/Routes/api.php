<?php

use App\Modules\Accounting\Http\Controllers\AccountingController;
use App\Modules\Accounting\Http\Controllers\AccountingAccountController;
use App\Modules\Accounting\Http\Controllers\AccountingGroupController;
use App\Modules\Accounting\Http\Controllers\AccountingStandardController;
use App\Modules\Accounting\Http\Controllers\BusinessStructureController;
use App\Modules\Accounting\Http\Controllers\ChartAccountController;
use App\Modules\Accounting\Http\Controllers\ExerciseVariationController;
use App\Modules\Accounting\Http\Controllers\TypeAccountController;
use App\Modules\Accounting\Http\Controllers\TypePlanController;
use Illuminate\Support\Facades\Route;

Route::prefix(config('accounting.route_prefix', 'api/accounting'))
    ->middleware(config('accounting.middleware', ['api']))
    ->name('accounting.')
    ->group(function (): void {
        Route::get('health', [AccountingController::class, 'health'])->name('health');
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
    });
