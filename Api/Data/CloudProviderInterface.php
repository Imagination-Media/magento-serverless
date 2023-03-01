<?php

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
