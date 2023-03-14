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

namespace ImDigital\Serverless\Block\Adminhtml\Function\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Backend\Block\Widget\Context;

class SaveButton implements ButtonProviderInterface
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * SaveButton constructor.
     * @param Context $context
     */
    public function __construct(
        Context $context
    ) {
        $this->context = $context;
    }

    /**
     * @return int
     */
    public function getPageId() : int
    {
        return (int)$this->context->getRequest()->getParam('id');
    }

    /**
     * @param string $route
     * @param array $params
     * @return string
     */
    public function getUrl($route = '', $params = []) : string
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }

    /**
     * @return array
     */
    public function getButtonData() : array
    {
        return [
            'label' => __('Save'),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'save']],
                'form-role' => 'save',
            ],
            'sort_order' => 20,
        ];
    }
}
