<?php

namespace Grayloon\Magento\Support;

use Grayloon\Magento\Magento;
use Grayloon\Magento\Models\MagentoProduct;

class MagentoSourceItems extends PaginatableMagentoService
{
    /**
     * The amount of total source items.
     *
     * @return int
     */
    public function count()
    {
        $items = (new Magento())->api('sourceItems')
            ->all($this->pageSize, $this->currentPage)
            ->json();

        return $items['total_count'];
    }

    /**
     * Updates product quantity from the Magento API.
     *
     * @param  array  $sourceItems
     * @return void
     */
    public function updateQuantities($sourceItems)
    {
        if (! $sourceItems) {
            return;
        }

        foreach ($sourceItems as $source) {
            $this->updateQuantity($source);
        }

        return $this;
    }

    /**
     * Updates a single product quantity.
     *
     * @param  array  $quantity
     * @return void
     */
    public function updateQuantity($source)
    {
        MagentoProduct::where('sku', $source['sku'])
            ->update([
                'quantity'    => $source['quantity'],
                'is_in_stock' => $source['status'],
            ]);

        return $this;
    }
}
