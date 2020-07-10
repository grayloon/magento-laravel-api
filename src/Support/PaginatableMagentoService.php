<?php

namespace Grayloon\Magento\Support;

class PaginatableMagentoService
{
    /**
     * The total count of records found.
     *
     * @var int
     */
    public $totalCount = 0;

    /**
     * The amount of records per API request.
     *
     * @var int
     */
    public $pageSize = 50;

    /**
     * The paginated page to request.
     *
     * @var int
     */
    public $currentPage = 1;

    /**
     * The total amount of pages to paginate.
     *
     * @var int
     */
    public $totalPages = 1;
}
