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
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Encryption\EncryptorInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Model\Context;
use Magento\Framework\Model\ResourceModel\AbstractResource;
use Magento\Framework\Registry;

class ServerlessFunction extends AbstractModel implements ServerlessFunctionInterface
{
    /**
     * @var EncryptorInterface
     */
    protected EncryptorInterface $encryptor;

    /**
     * @var string
     */
    // @codingStandardsIgnoreLine
    protected $_eventPrefix = 'serverless_function';

    /**
     * @var EncryptorInterface $encryptor
     * @var Context $context
     * @var Registry $registry
     * @var AbstractResource $resource
     * @var AbstractDb $resourceCollection
     */
    public function __construct(
        EncryptorInterface $encryptor,
        Context $context,
        Registry $registry,
        AbstractResource $resource = null,
        AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        $this->encryptor = $encryptor;
        parent::__construct($context, $registry, $resource, $resourceCollection, $data);
    }

    /**
     * @api
     * @return string
     */
    public function getName(): string
    {
        return (string) $this->getData(self::NAME);
    }

    /**
     * @api
     * @return string
     */
    public function getDescription(): string
    {
        return (string) $this->getData(self::DESCRIPTION);
    }

    /**
     * @api
     * @return string
     */
    public function getCloudProvider(): string
    {
        return (string) $this->getData(self::CLOUD_PROVIDER);
    }

    /**
     * @api
     * @return string
     */
    public function getObservedEvent(): string
    {
        return (string) $this->getData(self::OBSERVED_EVENT);
    }

    /**
     * @api
     * @return bool
     */
    public function getIsEnabled(): bool
    {
        return (bool) $this->getData(self::IS_ENABLED);
    }

    /**
     * @api
     * @param bool $decrypt
     * @return string
     */
    public function getCloudConfig(bool $decrypt = false): string
    {
        if (!$decrypt) {
            return (string) $this->getData(self::CLOUD_CONFIG);
        }
        return $this->encryptor->decrypt($this->getData(self::CLOUD_CONFIG));
    }

    /**
     * @api
     * @param string $name
     * @return void
     */
    public function setName(string $name): void
    {
        $this->setData(self::NAME, $name);
    }

    /**
     * @api
     * @param string $description
     * @return void
     */
    public function setDescription(string $description): void
    {
        $this->setData(self::DESCRIPTION, $description);
    }

    /**
     * @api
     * @param string $cloudProvider
     * @return void
     */
    public function setCloudProvider(string $cloudProvider): void
    {
        $this->setData(self::CLOUD_PROVIDER, $cloudProvider);
    }

    /**
     * @api
     * @param string $observedEvent
     * @return void
     */
    public function setObservedEvent(string $observedEvent): void
    {
        $this->setData(self::OBSERVED_EVENT, $observedEvent);
    }

    /**
     * @api
     * @param bool $isEnabled
     * @return void
     */
    public function setIsEnabled(bool $isEnabled): void
    {
        $this->setData(self::IS_ENABLED, $isEnabled);
    }

    /**
     * @api
     * @param string $cloudConfig
     * @param bool $encrypt
     * @return void
     */
    public function setCloudConfig(string $cloudConfig, bool $encrypt = true): void
    {
        if ($encrypt) {
            $cloudConfig = $this->encryptor->encrypt($cloudConfig);
        }
        $this->setData(self::CLOUD_CONFIG, $cloudConfig);
    }
}
