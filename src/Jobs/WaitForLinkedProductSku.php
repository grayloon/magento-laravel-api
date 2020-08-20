<?php

namespace Grayloon\Magento\Jobs;

use Exception;
use Grayloon\Magento\Magento;
use Grayloon\Magento\Models\MagentoProduct;
use Grayloon\Magento\Support\MagentoProducts;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class WaitForLinkedProductSku implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * The Magento Product.
     *
     * @var \Grayloon\Magento\Models\MagentoProduct
     */
    public $product;

    /**
     * The Magento API Response.
     *
     * @var array
     */
    public $response = [];

    /**
     * The current number of attempts we have tried to check if the product exists.
     *
     * @var int
     */
    public $attempts = 1;

    /**
     * The maximum number of attempts to stop.
     *
     * @var int
     */
    protected $maxAttempts = 3;

    /**
     * Create a new job instance.
     *
     * @param  \Grayloon\Magento\Models\MagentoProduct  $product
     * @return void
     */
    public function __construct(MagentoProduct $product, $response, $attempts = 1)
    {
        $this->product = $product;
        $this->response = $response;
        $this->attempts = $attempts;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $checkSku = MagentoProduct::where('sku', $this->response['linked_product_sku'])->first();

        if (! $checkSku) {
            if ($this->attempts >= $this->maxAttempts) {
                throw new Exception('Failed to find a product id with the Sku '.$this->response['linked_product_sku'].
                    ' to link with product id '.$this->product->id.' after '.$this->attempts.' attempts.');
            }

            return $this->dispatch($this->product, $this->response, $this->attempts++);
        }

        (new MagentoProducts())->updateProductLink($this->response, $this->product);
    }
}
