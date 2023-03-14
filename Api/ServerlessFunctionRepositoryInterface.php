<?php

/**
 * Serverless
 *
 * Use serverless function to modify the Magento business logic.
 *
 * @package ImDigital\Serverless
 * @author Igor Ludgero Miura <igor@imdigital.com>
 * @copyright Copyright (c) 2023 Imagination Media (https://www.imdigital.com/)
 * @license Private
 */

declare(strict_types=1);

namespace ImDigital\Serverless\Api;

use ImDigital\Serverless\Api\Data\ServerlessFunctionInterface;
use ImDigital\Serverless\Model\ResourceModel\ServerlessFunction\Collection;

interface ServerlessFunctionRepositoryInterface
{
    /**
     * @api
     * @param string $eventName
     * @return Collection
     */
    public function getFunctionsByEvent(?string $eventName = null): Collection;

    /**
     * @api
     * @param ServerlessFunctionInterface $serverlessFunctionInterface
     * @return bool
     */
    public function delete(ServerlessFunctionInterface $serverlessFunctionInterface): bool;

    /**
     * @api
     * @param ServerlessFunctionInterface $serverlessFunctionInterface
     * @return bool
     */
    public function save(ServerlessFunctionInterface $serverlessFunctionInterface): bool;
}
