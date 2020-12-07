<?php
namespace Delovunity\OutOfStock\Controller\Subscriptions;

use Magento\Framework\App\Action\Action;

class Index extends Action
{
    protected $resultPageFactory = false;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $customer;

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Customer\Model\Session $customer
    )
    {
        parent::__construct($context);
        $this->resultPageFactory = $resultPageFactory;
        $this->customer = $customer;
    }

    public function execute()
    {
        if($this->customer->isLoggedIn()){
            $resultPage = $this->resultPageFactory->create();
            $resultPage->getConfig()->getTitle()->prepend(__('Subscriptions'));
            return $resultPage;
        }
        else{
            $redirect = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_REDIRECT);
            $redirect->setUrl('/');
            $this->messageManager->addWarningMessage(__('You are not authorized'));
            return $redirect;
        }
    }
}
