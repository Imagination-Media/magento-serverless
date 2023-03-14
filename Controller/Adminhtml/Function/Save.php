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
use ImDigital\Serverless\Model\ServerlessFunctionFactory;
use ImDigital\Serverless\Model\ServerlessFunctionRepository;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultInterface;

class Save extends Action
{
    public const ADMIN_RESOURCE = 'ImDigital_Serverless::serverless_functions';

    /**
     * @var ServerlessFunctionFactory
     */
    protected ServerlessFunctionFactory $serverlessFunctionFactory;

    /**
     * @var ServerlessFunctionRepository
     */
    protected ServerlessFunctionRepository $serverlessFunctionRepository;

    /**
     * @param Context $context
     * @param ServerlessFunctionFactory $serverlessFunctionFactory
     * @param ServerlessFunctionRepository $serverlessFunctionRepository
     */
    public function __construct(
        Context $context,
        ServerlessFunctionFactory $serverlessFunctionFactory,
        ServerlessFunctionRepository $serverlessFunctionRepository
    ) {
        parent::__construct($context);
        $this->serverlessFunctionFactory = $serverlessFunctionFactory;
        $this->serverlessFunctionRepository = $serverlessFunctionRepository;
    }

    /**
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        $result = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getParams();
        unset($data['form_key']);
        unset($data['key']);

        // Remove ID if empty but part of the $data variable
        if (isset($data[ServerlessFunctionInterface::ID]) && empty($data[ServerlessFunctionInterface::ID])) {
            unset($data[ServerlessFunctionInterface::ID]);
        }

        $modelItem = $this->serverlessFunctionFactory->create();
        $modelItem->setData($data);
        $modelItem->setCloudConfig($modelItem->getCloudConfig());
        try {
            if ($this->serverlessFunctionRepository->save($modelItem)) {
                if (isset($data[ServerlessFunctionInterface::ID])) {
                    $this->messageManager->addSuccessMessage(__('Serverless function successfully updated'));
                } else {
                    $this->messageManager->addSuccessMessage(__('Serverless function successfully created'));
                    return $result->setUrl($this->_url->getUrl('serverless/function/index'));
                }
            } else {
                $this->messageManager->addErrorMessage(__('Serverless function not saved. Please check the logs'));
            }
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        }

        return $result->setUrl($this->_redirect->getRefererUrl());
    }
}
