<?php

namespace VCComponent\Laravel\Meta\Providers;

use Illuminate\Support\ServiceProvider;
use VCComponent\Laravel\Meta\Repositories\MetaRepository;
use VCComponent\Laravel\Meta\Repositories\MetaRepositoryEloquent;
use VCComponent\Laravel\Meta\Repositories\MetaSchemaOptionRepository;
use VCComponent\Laravel\Meta\Repositories\MetaSchemaOptionRepositoryEloquent;
use VCComponent\Laravel\Meta\Repositories\MetaSchemaRepository;
use VCComponent\Laravel\Meta\Repositories\MetaSchemaRepositoryEloquent;
use VCComponent\Laravel\Meta\Repositories\MetaSchemaRuleRepository;
use VCComponent\Laravel\Meta\Repositories\MetaSchemaRuleRepositoryEloquent;
use VCComponent\Laravel\Meta\Repositories\MetaSchemaTypeRepository;
use VCComponent\Laravel\Meta\Repositories\MetaSchemaTypeRepositoryEloquent;

class MetaServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/web.php');
        $this->loadRoutesFrom(__DIR__ . '/../../routes/api.php');
        $this->publishes([
            __DIR__ . '/../../config/meta.php' => config_path('meta.php'),
        ], 'config');
    }

    /**
     * Register any package services
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(MetaRepository::class, MetaRepositoryEloquent::class);
        $this->app->bind(MetaSchemaRepository::class, MetaSchemaRepositoryEloquent::class);
        $this->app->bind(MetaSchemaTypeRepository::class, MetaSchemaTypeRepositoryEloquent::class);
        $this->app->bind(MetaSchemaRuleRepository::class, MetaSchemaRuleRepositoryEloquent::class);
        $this->app->bind(MetaSchemaOptionRepository::class, MetaSchemaOptionRepositoryEloquent::class);
    }
}
