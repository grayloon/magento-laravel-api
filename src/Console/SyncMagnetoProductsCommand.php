<?php

namespace Grayloon\Magento\Console;

use Grayloon\Magento\Magento;
use Grayloon\Magento\Models\MagentoCustAttribute;
use Grayloon\Magento\Models\MagentoCustAttributeType;
use Grayloon\Magento\Models\MagentoExtAttribute;
use Grayloon\Magento\Models\MagentoExtAttributeType;
use Illuminate\Console\Command;
use Grayloon\Magento\Models\MagentoProduct;

class SyncMagnetoProductsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'magento:sync-products';

    /**
     * The progress bar to display to the user.
     *
     * @var object
     */
    public $bar;

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
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Imports the product data from the Magneto 2 API';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $products = Magento::api('products')->all($this->pageSize, $this->currentPage);
        $this->totalCount = $products['total_count'];
        $this->totalPages = ceil($this->totalCount / $this->pageSize) + 1;

        $this->bar = $this->output->createProgressBar($this->totalCount);

        for ($this->currentPage; $this->totalPages > $this->currentPage; $this->currentPage++) {
            $products = Magento::api('products')->all($this->pageSize, $this->currentPage);

            $this->updateProducts($products['items']);
        }

        $this->bar->finish();
    }

    protected function updateProducts($products)
    {
        foreach ($products as $product) {
            $magentoProduct = MagentoProduct::updateOrCreate(['id' => $product['id']], [
                'id'                   => $product['id'],
                'name'                 => $product['name'],
                'sku'                  => $product['sku'],
                'price'                => $product['price'] ?? 0,
                'status'               => $product['status'],
                'visibility'           => $product['visibility'],
                'type'                 => $product['type_id'],
                'created_at'           => $product['created_at'],
                'updated_at'           => $product['updated_at'],
                'weight'               => $product['weight'] ?? 0,
                'synced_at'            => now(),
            ]);

            foreach ($product['extension_attributes'] as $extAttributeKey => $extAttribute) {
                $attributeType = MagentoExtAttributeType::firstOrCreate(['type' => $extAttributeKey]);

                MagentoExtAttribute::updateOrCreate([
                    'magento_product_id'            => $magentoProduct->id,
                    'magento_ext_attribute_type_id' => $attributeType->id,
                ], ['attribute' => $extAttribute]);
            }

            foreach ($product['custom_attributes'] as $custAttribute) {
                $attributeType = MagentoCustAttributeType::firstOrCreate(['type' => $custAttribute['attribute_code']]);
                
                if (is_array($custAttribute['value'])) {
                    $custAttribute['value'] = json_encode($custAttribute['value']);
                }

                MagentoCustAttribute::updateOrCreate([
                    'magento_product_id'             => $magentoProduct->id,
                    'magento_cust_attribute_type_id' => $attributeType->id,
                ], ['attribute' => $custAttribute['value']]);
            }

            $this->bar->advance();
         }
    }
}
