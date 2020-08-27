<?php

namespace Grayloon\Magento\Jobs;

use Grayloon\Magento\Support\MagentoProducts;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncMagentoProducts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $products = new MagentoProducts();
        $totalPages = ceil(($products->count() / 100) + 1);

        for ($currentPage = 1; $totalPages > $currentPage; $currentPage++) {
            SyncMagentoProductsBatch::dispatch(100, $currentPage);
        }
    }
}
