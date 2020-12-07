<?php

namespace Delovunity\OutOfStock\Controller\Subscriptions;

use Magento\Framework\App\Action\Action;
use Delovunity\OutOfStock\Model\SubscriptionsFactory;

class Delete extends Action
{
    /**
     * @var SubscriptionsFactory
     */
    protected $subscriptionsFactory;

    protected $resultPageFactory = false;
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        SubscriptionsFactory $subscriptionsFactory
    )
    {
        parent::__construct($context);
        $this->subscriptionsFactory = $subscriptionsFactory;
        $this->resultPageFactory = $resultPageFactory;
    }

    public function execute()
    {
        $redirect = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
        $redirect->setUrl('/my_account_out_of_stock/subscriptions/index');
        $id = $this->getRequest()->getParam('id');
        try {
            $model = $this->subscriptionsFactory->create();
            $model->load($id);
            $model->delete();
            return $redirect;
        } catch (\Exception $e) {
            $this->messageManager->addError($e->getMessage());
            return $redirect;
        }
    }
}
