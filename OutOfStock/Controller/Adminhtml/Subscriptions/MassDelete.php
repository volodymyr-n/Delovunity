<?php

namespace Delovunity\OutOfStock\Controller\Adminhtml\Subscriptions;

use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Magento\Ui\Component\MassAction\Filter;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\Action;
use Delovunity\OutOfStock\Model\Subscriptions;
use Delovunity\OutOfStock\Model\ResourceModel\Subscriptions\Grid\CollectionFactory;

class MassDelete extends Action
{

    /**
     * @var CollectionFactory
     */
    protected $collectionFactory;
    /**
     * @var Filter
     */
    protected $filter;

    /**
     * @var Subscriptions
     */
    protected $subscriptions;

    /**
     * Constructor
     *
     * @param Context $context
     * @param Filter $filter
     * @param CollectionFactory $collectionFactory
     * @param Subscriptions $subscriptions
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionFactory $collectionFactory,
    Subscriptions $subscriptions
    ) {
        $this->filter = $filter;
        $this->collectionFactory = $collectionFactory;
        $this->subscriptions = $subscriptions;
        parent::__construct($context);
    }

    /**
     * Category delete action
     *
     * @return Redirect
     */
    public function execute(): Redirect
    {
        try {
            $collection = $this->filter->getCollection($this->collectionFactory->create());
            $collectionSize = $collection->getSize();
            foreach ($collection as $page) {
                $item = $this->subscriptions->load($page->getId());
                $item->delete();
            }
            $this->messageManager->addSuccessMessage('A total of %1 record(s) have been deleted.', $collectionSize);
        }catch (\Exception $e){
            $this->messageManager->addErrorMessage(
                __($e)
            );
        }
        return $this->resultFactory->create(ResultFactory::TYPE_REDIRECT)->setPath('outofstock/subscriptions/index');
    }
}
