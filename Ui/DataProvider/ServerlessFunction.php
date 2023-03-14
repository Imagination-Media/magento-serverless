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

namespace ImDigital\Serverless\Ui\DataProvider;

use ImDigital\Serverless\Model\ResourceModel\ServerlessFunction\Collection;
use ImDigital\Serverless\Model\ResourceModel\ServerlessFunction\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;

class ServerlessFunction extends AbstractDataProvider
{
    /**
     * @var Collection
     */
    protected $collection;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * @var array
     */
    protected $meta;

    /**
     * DataProvider constructor.
     * @param string|null $name
     * @param string|null $primaryFieldName
     * @param string|null $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        $this->meta = $meta;
    }

    /**
     * @return array|null
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        foreach ($items as $item) {
            // Decrypt cloud config
            $item->setCloudConfig($item->getCloudConfig(true), false);
            $this->loadedData[$item->getId()] = $item->getData();
        }
        $data = $this->dataPersistor->get('serverless_function');
        if (!empty($data)) {
            $item = $this->collection->getNewEmptyItem();
            $item->setData($data);
            $this->loadedData[$item->getId()] = $item->getData();
            $this->dataPersistor->clear('serverless_function');
        }
        return $this->loadedData;
    }
}
