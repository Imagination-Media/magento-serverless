<?php

declare(strict_types=1);

namespace ImDigital\Serverless\Api;

use ImDigital\Serverless\Model\ResourceModel\ServerlessFunction\Collection;

interface ServerlessFunctionRepositoryInterface
{
    /**
     * @api
     * @param string $eventName
     * @return Collection
     */
    public function getFunctionsByEvent(?string $eventName = null): Collection;
}
