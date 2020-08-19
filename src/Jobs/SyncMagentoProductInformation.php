<?php

namespace Grayloon\Magento\Jobs;

use Grayloon\Magento\Magento;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Grayloon\Magento\Models\MagentoProduct;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Grayloon\Magento\Models\MagentoStockItem;
use Grayloon\Magento\Support\MagentoStockItems;
use Grayloon\Magento\Support\MagentoProductLinks;

class SyncMagentoProductInformation implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The Magento Product.
     *
     * @var \Grayloon\Magento\Models\MagentoProduct
     */
    public $product;

    /**
     * Create a new job instance.
     *
     * @param  \Grayloon\Magento\Models\MagentoProduct  $product
     * @return void
     */
    public function __construct(MagentoProduct $product)
    {
        $this->product = $product;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $productApi = (new Magento())->api('products')
            ->show($this->product->sku)
            ->json();

        (new MagentoProductLinks())->updateProductLinks($this->product, $productApi);
        (new MagentoStockItems())->updateItemStock($productApi);
    }
}
