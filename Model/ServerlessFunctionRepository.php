<?php

declare(strict_types=1);

namespace ImDigital\Serverless\Model;

use ImDigital\Serverless\Api\Data\ServerlessFunctionInterface;
use ImDigital\Serverless\Api\ServerlessFunctionRepositoryInterface;
use ImDigital\Serverless\Model\ResourceModel\ServerlessFunction\Collection;
use ImDigital\Serverless\Model\ResourceModel\ServerlessFunction\CollectionFactory;

class ServerlessFunctionRepository implements ServerlessFunctionRepositoryInterface
{
    /**
     * @var CollectionFactory
     */
    protected CollectionFactory $serverlessFunctionCollectionFactory;

    /**
     * void
     */
    public function __construct(
        CollectionFactory $collectionFactory
    ) {
        $this->serverlessFunctionCollectionFactory = $collectionFactory;
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
}
