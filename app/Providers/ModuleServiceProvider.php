<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    /**
     * Register all module service providers found under app/Modules.
     */
    public function register(): void
    {
        foreach ($this->discoverModuleProviders() as $provider) {
            $this->app->register($provider);
        }
    }

    /**
     * Discover module providers using a simple filesystem convention.
     *
     * @return array<int, class-string>
     */
    protected function discoverModuleProviders(): array
    {
        $providers = [];

        foreach (glob(app_path('Modules/*/Providers/*ServiceProvider.php')) ?: [] as $path) {
            $relativePath = str($path)
                ->after(app_path().DIRECTORY_SEPARATOR)
                ->replace(DIRECTORY_SEPARATOR, '\\')
                ->replace('.php', '');

            $providers[] = 'App\\' . $relativePath;
        }

        sort($providers);

        return array_values(array_filter($providers, class_exists(...)));
    }
}
