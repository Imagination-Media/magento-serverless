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

namespace ImDigital\Serverless\Observer;

use ImDigital\Serverless\Api\ServerlessFunctionRepositoryInterface;
use ImDigital\Serverless\Model\ServerlessFunctionConfigRepository;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;

class GenerateConfig implements ObserverInterface
{
    /**
     * @var ServerlessFunctionRepositoryInterface
     */
    protected ServerlessFunctionRepositoryInterface $serverlessFunctionRepository;

    /**
     * @var ServerlessFunctionConfigRepository
     */
    protected ServerlessFunctionConfigRepository $serverlessFunctionConfigRepository;

    /**
     * GenerateConfig constructor.
     * @param ServerlessFunctionRepositoryInterface $serverlessFunctionRepository
     * @param ServerlessFunctionConfigRepository $serverlessFunctionConfigRepository
     */
    public function __construct(
        ServerlessFunctionRepositoryInterface $serverlessFunctionRepository,
        ServerlessFunctionConfigRepository $serverlessFunctionConfigRepository
    ) {
        $this->serverlessFunctionRepository = $serverlessFunctionRepository;
        $this->serverlessFunctionConfigRepository = $serverlessFunctionConfigRepository;
    }

     /**
     * @param Observer $observer
     * @return void
     */
    public function execute(Observer $observer)
    {
        // Get all enabled functions
        $enabledFunctions = $this->serverlessFunctionRepository->getFunctionsByEvent();
        $this->serverlessFunctionConfigRepository->generateConfigFile($enabledFunctions);
    }
}
