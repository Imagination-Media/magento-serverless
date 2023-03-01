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

namespace ImDigital\Serverless\Event;

use ImDigital\Serverless\Model\ServerlessFunctionConfigRepository;
use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\Event\ConfigInterface;
use Magento\Framework\Event\InvokerInterface;
use Magento\Framework\Event\Manager as CoreManager;
use Magento\Framework\Event\Observer;

class Manager extends CoreManager
{
    /**
     * @var DeploymentConfig
     */
    protected DeploymentConfig $deploymentConfig;

    /**
     * @var ServerlessFunctionConfigRepository
     */
    protected ServerlessFunctionConfigRepository $serverlessFunctionConfigRepository;

    /**
     * Manager constructor.
     * @param InvokerInterface $invoker
     * @param ConfigInterface $eventConfig
     * @param DeploymentConfig $deploymentConfig
     * @param ServerlessFunctionConfigRepository $serverlessFunctionConfigRepository
     */
    public function __construct(
        InvokerInterface $invoker,
        ConfigInterface $eventConfig,
        DeploymentConfig $deploymentConfig,
        ServerlessFunctionConfigRepository $serverlessFunctionConfigRepository
    ) {
        parent::__construct($invoker, $eventConfig);
        $this->_invoker = $invoker;
        $this->_eventConfig = $eventConfig;
        $this->deploymentConfig = $deploymentConfig;
        $this->serverlessFunctionConfigRepository = $serverlessFunctionConfigRepository;
    }

    /**
     * @param string $eventName
     * @param array $data
     * @return void
     */
    public function dispatch($eventName, array $data = [])
    {
        $eventName = $eventName !== null ? mb_strtolower($eventName) : '';
        \Magento\Framework\Profiler::start('EVENT:' . $eventName, ['group' => 'EVENT', 'name' => $eventName]);
        
        /**
         * Execute internal observers that are part of the Magento codebase
         */
        foreach ($this->_eventConfig->getObservers($eventName) as $observerConfig) {
            $event = new \Magento\Framework\Event($data);
            $event->setName($eventName);

            $wrapper = new Observer();
            // phpcs:ignore Magento2.Performance.ForeachArrayMerge
            $wrapper->setData(array_merge(['event' => $event], $data));

            \Magento\Framework\Profiler::start('OBSERVER:' . $observerConfig['name']);
            $this->_invoker->dispatch($observerConfig, $wrapper);
            \Magento\Framework\Profiler::stop('OBSERVER:' . $observerConfig['name']);
        }

        /**
         * Execute serverless functions starting from the synchronous ones and then go to the asynchronous functions
         * @var $serverlessFunction \ImDigital\Serverless\Model\ServerlessFunction
         */
        if ($eventName) {
            // if ($eventName == 'catalog_product_save_after') {
            //     $a = 1;
            // }
            foreach ($this->serverlessFunctionConfigRepository->getFunctionsByEvent($eventName) as $serverlessFunction
            ) {
                \Magento\Framework\Profiler::start('SERVERLESS:' . $serverlessFunction->getName());
                $this->serverlessFunctionConfigRepository->executeFunction($serverlessFunction, $data);
                \Magento\Framework\Profiler::stop('SERVERLESS:' . $serverlessFunction->getName());
            }
        }

        \Magento\Framework\Profiler::stop('EVENT:' . $eventName);
    }
}
