<?php

namespace Grayloon\Magento\Jobs;

use Grayloon\Magento\Magento;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncMagentoCategories implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $rootCategory = Magento::api('categories')->all(config('magento.default_category'));

        if (empty($rootCategory['children_data'])) {
            return;
        }

        foreach ($rootCategory['children_data'] as $category) {
            ResolveMagentoCategory::dispatch($category);
        }
    }
}
