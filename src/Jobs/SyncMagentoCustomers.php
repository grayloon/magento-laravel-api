<?php

namespace Grayloon\Magento\Jobs;

use Grayloon\Magento\Support\MagentoCustomers;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncMagentoCustomers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $customers = new MagentoCustomers();
        $totalPages = ceil(($customers->count() / 50) + 1);

        for ($currentPage = 1; $totalPages > $currentPage; $currentPage++) {
            SyncMagentoCustomersBatch::dispatch(50, $currentPage);
        }
    }
}
