<?php

namespace Grayloon\Magento\Jobs;

use Grayloon\Magento\Support\MagentoSourceItems;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncMagentoProductQuantity implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $sourceItems = new MagentoSourceItems();
        $totalPages = ceil(($sourceItems->count() / 50) + 1);

        for ($currentPage = 1; $totalPages > $currentPage; $currentPage++) {
            SyncMagentoProductQuantityBatch::dispatch(50, $currentPage);
        }
    }
}
