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

interface ServerlessFunctionInterface
{
    public const ID              = 'id';
    public const NAME            = 'name';
    public const DESCRIPTION     = 'description';
    public const CLOUD_PROVIDER  = 'cloud_provider';
    public const OBSERVED_EVENT  = 'observer_event';
    public const IS_ENABLED      = 'is_enabled';
    public const CLOUD_CONFIG    = 'cloud_config';

    public const TABLE_NAME      = "serverless_functions";

    /**
     * @api
     * @return string
     */
    public function getName(): string;

    /**
     * @api
     * @return string
     */
    public function getDescription(): string;

    /**
     * @api
     * @return string
     */
    public function getCloudProvider(): string;

    /**
     * @api
     * @return string
     */
    public function getObservedEvent(): string;

    /**
     * @api
     * @return bool
     */
    public function getIsEnabled(): bool;

    /**
     * @api
     * @param bool $decrypt
     * @return string
     */
    public function getCloudConfig(bool $decrypt = false): string;

    /**
     * @api
     * @param string $name
     * @return void
     */
    public function setName(string $name): void;

    /**
     * @api
     * @param string $description
     * @return void
     */
    public function setDescription(string $description): void;

    /**
     * @api
     * @param string $cloudProvider
     * @return void
     */
    public function setCloudProvider(string $cloudProvider): void;

    /**
     * @api
     * @param string $observedEvent
     * @return void
     */
    public function setObservedEvent(string $observedEvent): void;

    /**
     * @api
     * @param bool $isEnabled
     * @return void
     */
    public function setIsEnabled(bool $isEnabled): void;

    /**
     * @api
     * @param string $cloudConfig
     * @param bool $encrypt
     * @return void
     */
    public function setCloudConfig(string $cloudConfig, bool $encrypt = true): void;
}
