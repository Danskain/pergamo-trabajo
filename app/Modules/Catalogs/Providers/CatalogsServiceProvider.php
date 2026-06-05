<?php

namespace App\Modules\Catalogs\Providers;

use Illuminate\Support\ServiceProvider;

class CatalogsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../Config/catalogs.php', 'catalogs');
    }

    public function boot(): void
    {
        $this->loadRoutesFrom(__DIR__.'/../Routes/api.php');
    }
}
