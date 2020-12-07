<?php

namespace Delovunity\OutOfStock\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Magento\Customer\Model\Session;

class Data extends AbstractHelper
{

    /**
     * @var Session
     */
    private $customerSession;

    const XML_PATH_HELLOWORLD = 'out_of_stock/';


    public function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            $field, ScopeInterface::SCOPE_STORE, $storeId
        );
    }

    public function getGeneralConfig($code, $storeId = null)
    {
        return $this->getConfigValue(self::XML_PATH_HELLOWORLD .'general/'. $code, $storeId);
    }

    public function isFrontEnd(){
        return (bool)$this->getGeneralConfig('outofstock_frontend');
    }

    public function isEmailMailings(){
        return (bool)$this->getGeneralConfig('outofstock_email_mailings');
    }

    public function getEmailCustomer(){
        $customerSession=new Session;
        $customerSession->start();
        if($customerSession->isLoggedIn()) {
            return 1;
        } else {
            return 0;
        }
    }
}
