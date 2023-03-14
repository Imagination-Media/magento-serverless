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
 
use Magento\Backend\Block\Widget\Context;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
 
class DeleteButton implements ButtonProviderInterface
{
    /**
     * @var Context
     */
    protected Context $context;
 
    /**
     * @param Context $context
     */
    public function __construct(Context $context)
    {
        $this->context = $context;
    }
 
    /**
     * @return array
     */
    public function getButtonData(): array
    {
        return [
            'label' => __('Delete'),
            'on_click' => sprintf("if (confirm('%s')) { location.href = '%s'; }", __('Are you sure to disconnect and remove this function?'), $this->context->getUrlBuilder()->getUrl('*/*/massDelete', ['selected' => $this->context->getRequest()->getParam('id')])),
            'class' => 'delete',
            'sort_order' => 10
        ];
    }
}
