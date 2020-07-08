<?php

namespace Grayloon\Magento\Support;

class PaginatableMagentoService
{
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
}
