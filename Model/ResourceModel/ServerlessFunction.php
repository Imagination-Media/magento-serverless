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

namespace ImDigital\Serverless\Model\ResourceModel;

use ImDigital\Serverless\Api\Data\ServerlessFunctionInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ServerlessFunction extends AbstractDb
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ServerlessFunctionInterface::TABLE_NAME, ServerlessFunctionInterface::ID);
    }
}
