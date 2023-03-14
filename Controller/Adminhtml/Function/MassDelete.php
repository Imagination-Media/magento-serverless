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

namespace ImDigital\Serverless\Controller\Adminhtml\Function;

use ImDigital\Serverless\Api\Data\ServerlessFunctionInterface;
use ImDigital\Serverless\Controller\Adminhtml\Function\Index;
use ImDigital\Serverless\Model\ResourceModel\ServerlessFunction\Collection;
use ImDigital\Serverless\Model\ResourceModel\ServerlessFunction\CollectionFactory;
use ImDigital\Serverless\Model\ServerlessFunctionFactory;
use ImDigital\Serverless\Model\ServerlessFunctionRepository;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;

class MassDelete extends Action
{
    const ADMIN_RESOURCE = Index::ADMIN_RESOURCE;

    /**
     * @var Action\Context
     */
    protected Action\Context $context;

    /**
     * @var ServerlessFunctionRepository
     */
    protected ServerlessFunctionRepository $serverlessFunctionRepository;

    /**
     * @var Filter
     */
    protected Filter $filter;

    /**
     * @var ServerlessFunctionFactory
     */
    protected ServerlessFunctionFactory $serverlessFunctionFactory;

    /**
     * @var CollectionFactory
     */
    protected CollectionFactory $collectionFactory;

    /**
     * MassDelete constructor.
     * @param Context $context
     * @param ServerlessFunctionRepository $serverlessFunctionRepository
     * @param ServerlessFunctionFactory $serverlessFunctionFactory
     * @param CollectionFactory $collectionFactory
     * @param Filter $filter
     */
    public function __construct(
        Action\Context $context,
        ServerlessFunctionRepository $serverlessFunctionRepository,
        ServerlessFunctionFactory $serverlessFunctionFactory,
        CollectionFactory $collectionFactory,
        Filter $filter
    ) {
        parent::__construct($context);
        $this->context = $context;
        $this->serverlessFunctionRepository = $serverlessFunctionRepository;
        $this->serverlessFunctionFactory = $serverlessFunctionFactory;
        $this->collectionFactory = $collectionFactory;
        $this->filter = $filter;
    }

    /**
     * @return ResultInterface
     * @throws LocalizedException
     */
    public function execute() : ResultInterface
    {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);

        // Get the selected ids
        $ids = [];
        if ($ids = $this->context->getRequest()->getParam('selected')) {
            if (!is_array($ids)) {
                $ids = [$ids];
            }
        } else {
            $ids = $this->filter->getComponent()->getContext()->getRequestParam('selected');
        }

        // Create collection
        /**
         * @var Collection $collection
         */
        $collection = $this->collectionFactory->create();

        // Filter by ids
        $collection->addFieldToFilter(ServerlessFunctionInterface::ID, ['in' => $ids]);

        $count = $collection->count();

        if ($count > 0) {
            foreach ($collection as $item) {
                $obj = $this->serverlessFunctionFactory->create();
                $obj->setData($item->getData());
                $this->serverlessFunctionRepository->delete($obj);
            }

            if ($count > 1) {
                $this->messageManager->addSuccessMessage(
                    __('%1 items were removed.', $count)
                );
            } else {
                $this->messageManager->addSuccessMessage(
                    __('An item was removed.')
                );
            }
        } else {
            $this->messageManager->addErrorMessage(__("There is no item to be removed."));
        }

        $resultRedirect->setUrl($this->_url->getUrl('serverless/function/index'));
        return $resultRedirect;
    }
}
