<?php

namespace EnumTools;

use Illuminate\Support\ServiceProvider;

class EnumToolsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/config/enum_tools.php' => config_path('enum_tools.php'),
        ], 'config');
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/config/enum_tools.php', 'enum_tools');
    }
}
