<?php

namespace Grayloon\Magento\Jobs;

use Grayloon\Magento\Magento;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Grayloon\Magento\Support\MagentoProducts;

class SyncMagentoProductsBatch implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $pageSize;
    public $requestedPage;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($pageSize, $requestedPage)
    {
        $this->pageSize = $pageSize;
        $this->requestedPage = $requestedPage;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $products = Magento::api('products')->all($this->pageSize, $this->requestedPage);

        (new MagentoProducts)->updateProducts($products['items']);
    }
}
