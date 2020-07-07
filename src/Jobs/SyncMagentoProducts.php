<?php

namespace Grayloon\Magento\Jobs;

use Grayloon\Magento\Magento;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Grayloon\Magento\Jobs\SyncMagentoProductsBatch;

class SyncMagentoProducts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The total count of records found.
     *
     * @var integer
     */
    public $totalCount = 0;

    /**
     * The amount of records per API request.
     *
     * @var integer
     */
    public $pageSize = 50;

    /**
     * The paginated page to request.
     *
     * @var integer
     */
    public $currentPage = 1;

    /**
     * The total amount of pages to paginate.
     *
     * @var integer
     */
    public $totalPages = 1;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $products = Magento::api('products')->all($this->pageSize, $this->currentPage);
        $this->totalCount = $products['total_count'];
        $this->totalPages = ceil($this->totalCount / $this->pageSize) + 1;

        for ($this->currentPage; $this->totalPages > $this->currentPage; $this->currentPage++) {
            SyncMagentoProductsBatch::dispatch($this->pageSize, $this->currentPage);
        }
    }
}
