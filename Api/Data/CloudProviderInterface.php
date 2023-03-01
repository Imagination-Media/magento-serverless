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

namespace ImDigital\Serverless\Api\Data;

interface CloudProviderInterface
{
    /**
     * @param ServerlessFunctionInterface $serverlessFunction
     * @param array $data
     * @throws \Exception
     * @return void
     */
    public function execute(ServerlessFunctionInterface $serverlessFunction, array &$data): void;

    /**
     * @param ServerlessFunctionInterface $serverlessFunction
     */
    public function getCloudConfig(ServerlessFunctionInterface $serverlessFunction);
}
