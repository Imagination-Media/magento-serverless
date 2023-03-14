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

namespace ImDigital\Serverless\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Provider implements OptionSourceInterface
{
    /**
     * @var array
     */
    protected array $cloudProviders = [];

    /**
     * @param array $providers
     */
    public function __construct(
        array $cloudProviders = []
    ) {
        $this->cloudProviders = $cloudProviders;
    }

    /**
     * @return array
     */
    public function toOptionArray(): array
    {
        return array_merge(
            [
                [
                    'label' => __('-- Please Select --'),
                    'value' => ''
                ]
            ],
            $this->cloudProviders
        );
    }
}
