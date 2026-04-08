<?php

namespace App\Support\Monetization;

use Illuminate\Support\Arr;

class MonetizationPolicy
{
    public function shouldShowSlots(string $pageType, bool $isIndexable = true): bool
    {
        if (! app(\App\Support\Project\ModuleManager::class)->enabled('monetization')) {
            return false;
        }

        if (! (bool) config('project.monetization.adsense_ready')) {
            return false;
        }

        if (! (bool) config('project.monetization.show_ad_slots')) {
            return false;
        }

        if (! $isIndexable) {
            return false;
        }

        return ! in_array($pageType, Arr::wrap(config('project.monetization.disabled_page_types', [])), true);
    }

    public function slotsFor(string $pageType): array
    {
        return Arr::wrap(Arr::get(config('project.monetization.per_page_type', []), $pageType, []));
    }
}
