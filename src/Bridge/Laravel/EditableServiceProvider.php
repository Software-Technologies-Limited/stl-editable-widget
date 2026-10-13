<?php

declare(strict_types=1);

namespace Stl\Editable\Bridge\Laravel;

use Illuminate\Support\ServiceProvider;

final class EditableServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../../../config/stl-editable.php', 'stl-editable');
    }

    public function boot(): void
    {
        $this->publishes([__DIR__ . '/../../../config/stl-editable.php' => config_path('stl-editable.php')], 'stl-editable-config');
        $this->publishes([__DIR__ . '/../../assets/stl-editable.css' => public_path('vendor/stl-editable/stl-editable.css'), __DIR__ . '/../../assets/stl-editable.js' => public_path('vendor/stl-editable/stl-editable.js')], 'stl-editable-assets');
    }
}
