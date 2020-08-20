<?php

namespace Grayloon\Magento\Jobs;

use Grayloon\Magento\Magento;
use Grayloon\Magento\Support\MagentoSourceItems;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncMagentoProductQuantityBatch implements ShouldQueue
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
        $sourceItems = (new Magento())->api('sourceItems')
            ->all($this->pageSize, $this->requestedPage)
            ->json();

        (new MagentoSourceItems())->updateQuantities($sourceItems['items']);
    }
}
