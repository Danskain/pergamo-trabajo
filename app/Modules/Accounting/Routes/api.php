<?php

use App\Modules\Accounting\Http\Controllers\AccountingController;
use App\Modules\Accounting\Http\Controllers\AccountingStandardController;
use App\Modules\Accounting\Http\Controllers\ExerciseVariationController;
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
        
        Route::get('types-plans', [TypePlanController::class, 'index'])->name('types-plans.index');
        Route::post('types-plans', [TypePlanController::class, 'store'])->name('types-plans.store');
        Route::get('types-plans/{typePlan}', [TypePlanController::class, 'show'])->name('types-plans.show');
        Route::put('types-plans/{typePlan}', [TypePlanController::class, 'update'])->name('types-plans.update');
        Route::delete('types-plans/{typePlan}', [TypePlanController::class, 'destroy'])->name('types-plans.destroy');
        Route::post('types-plans/{typePlan}/restore', [TypePlanController::class, 'restore'])->name('types-plans.restore');
    });
