<?php

namespace Grayloon\Magento\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class MagentoProductController extends Controller
{
    /**
     * Launches a job to create or update the
     * specified SKU from the Magento API.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $sku
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $sku)
    {
        // Sync Magento Product

        return response()->json(['success' => 'success'], 200);
    }
}
