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
use ImDigital\Serverless\Model\Cloud\Provider as AbstractProvider;
use ImDigital\Serverless\Model\ResourceModel\ServerlessFunction\Collection;
use ImDigital\Serverless\Model\ServerlessFunction;
use ImDigital\Serverless\Model\ServerlessFunctionFactory;
use Magento\Framework\App\ObjectManager;
use Psr\Log\LoggerInterface;

class ServerlessFunctionConfigRepository
{
    public const FILE_PATH = "var/serverless/functions.json";

    /**
     * @var LoggerInterface
     */
    protected LoggerInterface $logger;

    /**
     * @var ServerlessFunctionFactory
     */
    protected ServerlessFunctionFactory $serverlessFunctionFactory;

    /**
     * @var array
     */
    protected array $cloudProviders = [];

    /**
     * ServerlessFunctionConfigRepository constructor.
     * @param DeploymentConfig $deploymentConfig
     * @param ServerlessFunctionConfigRepository $serverlessFunctionConfigRepository
     * @param array $cloudProviders
     */
    public function __construct(
        LoggerInterface $logger,
        ServerlessFunctionFactory $serverlessFunctionFactory,
        array $cloudProviders = []
    ) {
        $this->logger = $logger;
        $this->serverlessFunctionFactory = $serverlessFunctionFactory;
        $this->cloudProviders = $cloudProviders;
    }

    /**
     * Generate serverless config file
     * @param Collection $collection
     * @return bool
     */
    public function generateConfigFile(Collection $collection): bool
    {
        // Create a directory using the Magento webroot as the root
        $filePath = isset($_ENV['MAGENTO_SERVERLESS_FILE_PATH'])
            ? $_ENV['MAGENTO_SERVERLESS_FILE_PATH'] : BP . DIRECTORY_SEPARATOR . self::FILE_PATH;
        
        $directory = dirname($filePath);

        // Create the directory if it doesn't exist
        try {
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }
        } catch (\Exception $e) {
            $this->logger->error("Can't create the {$directory} directory: " . $e->getMessage());
            return false;
        }

        $jsonContent = [
            "functions" => []
        ];

        /**
         * @var ServerlessFunction $serverlessFunction
         */
        foreach ($collection as $serverlessFunction) {
            $data = $serverlessFunction->getData();
            $data[ServerlessFunctionInterface::CLOUD_CONFIG] =
                json_decode($serverlessFunction->getCloudConfig(true), true);
            $jsonContent["functions"][] = $data;
        }

        // Loop through all cloud provider froms the $cloudProviders variable, and add them to the $jsonContent variable on the "cloudProviders" key
        foreach ($this->cloudProviders as $providerCode => $providerClass) {
            $jsonContent["cloudProviders"][$providerCode] = $providerClass::class;
        }

        // Write the file
        try {
            file_put_contents($filePath, json_encode($jsonContent));
        } catch (\Exception $e) {
            $this->logger->error("Can't create the {$filePath} JSON file: " . $e->getMessage());
            return false;
        }

        return true;
    }

    /**
     * Execute serverless function
     */
    public function executeFunction(ServerlessFunctionInterface $serverlessFunction, array &$data): void
    {
        if (!isset($this->cloudProviders[$serverlessFunction->getCloudProvider()])) {
            throw new \Exception("Cloud provider {$serverlessFunction->getCloudProvider()} not supported!");
        }

        /**
         * @var \ImDigital\Serverless\Model\CloudProvider\CloudProviderInterface $cloudProvider
         */
        $cloudProvider = $this->cloudProviders[$serverlessFunction->getCloudProvider()];
        $cloudProvider->execute($serverlessFunction, $data);
    }

    /**
     * Get functions by event
     * @param string|null $eventName
     * @return ServerlessFunctionInterface[]
     */
    public function getFunctionsByEvent(?string $eventName = null): array
    {
        $filePath = isset($_ENV['MAGENTO_SERVERLESS_FILE_PATH'])
            ? $_ENV['MAGENTO_SERVERLESS_FILE_PATH'] : BP . DIRECTORY_SEPARATOR . self::FILE_PATH;

        if (!file_exists($filePath)) {
            $this->logger->error("Can't find the {$filePath} JSON file when trying to get functions by event.");
            return [];
        }

        $jsonContent = json_decode(file_get_contents($filePath), true);

        if (!isset($jsonContent["functions"])) {
            $this->logger->error("Can't find the functions key in the {$filePath} JSON file.");
            return [];
        }

        $functions = [];

        foreach ($jsonContent["functions"] as $function) {
            if ($eventName === null || $function["observed_event"] === $eventName) {
                $newFunction = $this->serverlessFunctionFactory->create();

                // Convert the cloud config to a JSON again for compatibility
                $function[ServerlessFunctionInterface::CLOUD_CONFIG] =
                    json_encode($function[ServerlessFunctionInterface::CLOUD_CONFIG]);

                $newFunction->setData($function);
                $functions[] = $newFunction;
            }
        }

        return $functions;
    }

    /**
     * Get cloud provider by code
     * This is going to be used on the shipping and payment serverless modules, that's why it's using object manager
     * Please avoid to use this method if not in the context described above
     * @param string $code
     * @return AbstractProvider
     * @throws \Exception
     */
    public function getCloudProviderByCode(string $code): AbstractProvider
    {
        $filePath = isset($_ENV['MAGENTO_SERVERLESS_FILE_PATH'])
            ? $_ENV['MAGENTO_SERVERLESS_FILE_PATH'] : BP . DIRECTORY_SEPARATOR . self::FILE_PATH;

        if (!file_exists($filePath)) {
            $this->logger->error("Can't find the {$filePath} JSON file when trying to get the cloud providers.");
            return [];
        }

        $jsonContent = json_decode(file_get_contents($filePath), true);

        if (!isset($jsonContent["cloudProviders"])) {
            $this->logger->error("Can't find the cloudProviders key in the {$filePath} JSON file.");
            return [];
        }

        if (!isset($jsonContent["cloudProviders"][$code])) {
            $this->logger->error("Can't find the cloud provider {$code} in the {$filePath} JSON file.");
            return [];
        }

        return ObjectManager::getInstance()->create($jsonContent["cloudProviders"][$code]);
    }
}
