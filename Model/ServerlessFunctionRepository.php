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

namespace ImDigital\Serverless\Model;

use ImDigital\Serverless\Api\Data\ServerlessFunctionInterface;
use ImDigital\Serverless\Api\ServerlessFunctionRepositoryInterface;
use ImDigital\Serverless\Model\ResourceModel\ServerlessFunction as ResourceModel;
use ImDigital\Serverless\Model\ResourceModel\ServerlessFunction\Collection;
use ImDigital\Serverless\Model\ResourceModel\ServerlessFunction\CollectionFactory;

class ServerlessFunctionRepository implements ServerlessFunctionRepositoryInterface
{
    /**
     * @var CollectionFactory
     */
    protected CollectionFactory $serverlessFunctionCollectionFactory;

    /**
     * @var ResourceModel
     */
    protected ResourceModel $resourceModel;

    /**
     * @param CollectionFactory $collectionFactory
     * @param ResourceModel $resourceModel
     */
    public function __construct(
        CollectionFactory $collectionFactory,
        ResourceModel $resourceModel
    ) {
        $this->serverlessFunctionCollectionFactory = $collectionFactory;
        $this->resourceModel = $resourceModel;
    }
    
    /**
     * @api
     * @param string $eventName
     * @return Collection
     */
    public function getFunctionsByEvent(?string $eventName = null): Collection
    {
        /**
         * @var Collection|ServerlessFunctionInterface[] $collection
         */
        $collection = $this->serverlessFunctionCollectionFactory->create();

        if ($eventName) {
            $collection->addFieldToFilter(ServerlessFunctionInterface::OBSERVED_EVENT, $eventName);
        }

        $collection->addFieldToFilter(ServerlessFunctionInterface::IS_ENABLED, 1);
        return $collection;
    }

    /**
     * @api
     * @param ServerlessFunctionInterface $serverlessFunctionInterface
     * @return bool
     */
    public function delete(ServerlessFunctionInterface $serverlessFunctionInterface): bool
    {
        try {
            $this->resourceModel->delete($serverlessFunctionInterface);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @api
     * @param ServerlessFunctionInterface $serverlessFunctionInterface
     * @return bool
     */
    public function save(ServerlessFunctionInterface $serverlessFunctionInterface): bool
    {
        try {
            $this->resourceModel->save($serverlessFunctionInterface);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
