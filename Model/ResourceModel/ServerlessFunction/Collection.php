<?php

declare(strict_types=1);

namespace ImDigital\Serverless\Model\ResourceModel\ServerlessFunction;

use ImDigital\Serverless\Model\ResourceModel\ServerlessFunction as ResourceModel;
use ImDigital\Serverless\Model\ServerlessFunction;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(
            ServerlessFunction::class,
            ResourceModel::class
        );
    }
}
