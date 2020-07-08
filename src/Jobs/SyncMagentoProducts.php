<?php

namespace Grayloon\Magento\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Grayloon\Magento\Support\MagentoProducts;

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
        $totalPages = ceil(($products->count() / 50) + 1);

        for ($currentPage = 1; $totalPages > $currentPage; $currentPage++) {
            SyncMagentoProductsBatch::dispatch(50, $currentPage);
        }
    }
}
