<?php

namespace App\Support\Project;

use Illuminate\Support\Arr;

class ModuleManager
{
    public function all(): array
    {
        return (array) config('project.modules', []);
    }

    public function enabled(string $module): bool
    {
        $modules = $this->all();
        $moduleEnabled = (bool) Arr::get($modules, $module, false);

        $profileModules = Arr::wrap(Arr::get(config('project.project_profiles', []), config('project.project_type'), [])['modules'] ?? []);

        if ($profileModules === []) {
            return $moduleEnabled;
        }

        return $moduleEnabled && in_array($module, $profileModules, true);
    }

    public function enabledModules(): array
    {
        return array_values(array_keys(array_filter($this->all(), fn ($isEnabled, $module) => $this->enabled((string) $module), ARRAY_FILTER_USE_BOTH)));
    }
}
