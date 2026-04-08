<?php

namespace App\Support\Project;

use Illuminate\Support\Arr;

class ProjectProfile
{
    public function type(): string
    {
        return (string) config('project.project_type', 'content');
    }

    public function config(): array
    {
        $type = $this->type();

        return (array) Arr::get(config('project.project_profiles', []), $type, []);
    }

    public function preferredLayout(): string
    {
        return (string) Arr::get($this->config(), 'layout', 'default');
    }

    public function preferredSchema(): array
    {
        return Arr::wrap(Arr::get($this->config(), 'preferred_schema', ['WebPage']));
    }
}
