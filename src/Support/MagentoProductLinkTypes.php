<?php

namespace Grayloon\Magento\Support;

use Grayloon\Magento\Magento;
use Grayloon\Magento\Models\MagentoProductLinkType;

class MagentoProductLinkTypes
{
    /**
     * Store the Magento Product Types from the API.
     *
     * @param  array  $types
     * @return void
     */
    public function storeTypes($types)
    {
        foreach ($types as $type) {
            if (! isset($type['code'])) {
                continue;
            }

            MagentoProductLinkType::updateOrCreate(['id' => $type['code']], [
                'name'      => $type['name'] ?? $type['code'],
                'synced_at' => now(),
            ]);
        }

        return $this;
    }
}
