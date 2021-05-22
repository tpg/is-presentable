<?php

declare(strict_types=1);

namespace TPG\IsPresentable;

use Illuminate\Support\ServiceProvider;

class IsPresentableServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/presentable.php', 'presentable'
        );
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/../config/presentable.php' => config_path('presentable.php'),
        ]);
    }
}
