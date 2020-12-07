<?php

namespace Delovunity\OutOfStock\Helper;

use \Magento\Framework\Mail\Template\TransportBuilder;
use \Magento\Framework\Translate\Inline\StateInterface;
use Delovunity\OutOfStock\Observer\ChangeStockStatus;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Helper\AbstractHelper;

class Email extends AbstractHelper
{
    const XML_PATH_EMAIL_ADMIN_QUOTE_NOTIFICATION = 'email_template';

    /**
     * @var StateInterface
     */
    protected $inlineTranslation;

    /**
     * @var TransportBuilder
     */
    protected $transportBuilder;

    /**
     * @var LoggerInterface
     */
    protected $_logLoggerInterface;
    private $sender_email;
    private $sender_name;
    private $receiver_email;
    private $product_name;
    private $url;

    public function __construct(
        Context $context,
        StateInterface $inlineTranslation,
        TransportBuilder $transportBuilder,
        LoggerInterface $logLoggerInterface)
    {
        parent::__construct($context);
        $this->inlineTranslation = $inlineTranslation;
        $this->transportBuilder = $transportBuilder;
        $this->_logLoggerInterface = $logLoggerInterface;
    }

    public function execute()
    {
        try
        {
            $this->inlineTranslation->suspend();
            $transport = $this->transportBuilder
                ->setTemplateIdentifier(self::XML_PATH_EMAIL_ADMIN_QUOTE_NOTIFICATION)
                ->setTemplateOptions(
                    [
                        'area' => 'adminhtml',
                        'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                    ]
                )
                ->setTemplateVars([
                    'product_name'  => $this->product_name,
                    'email'  => $this->sender_email,
                    'name'  => $this->sender_name,
                    'url'  => $this->url
                ])
                ->setFrom([
                    'name'=>$this->sender_name,
                    'email'=>$this->sender_email
                ])
                ->addTo($this->receiver_email)
                ->getTransport();
            $transport->sendMessage();
        } catch(\Exception $e){
            $this->_logLoggerInterface->debug("Delovunity\OutOfStock\Helper Error send from email. ERROR:'". $e->getMessage()."'");
        exit;
        }
    }

    /**
     * @param ChangeStockStatus $changeStockStatus
     * @throws \Magento\Framework\Exception\NotFoundException
     */
   public function sendEmail(ChangeStockStatus $changeStockStatus){
        $this->product_name = $changeStockStatus->getProductName();
        $this->sender_email = $changeStockStatus->getSenderEmail();
        $this->sender_name= $changeStockStatus->getSenderName();
        $this->url = $changeStockStatus->getSiteUrl();
        foreach ($changeStockStatus->getReceiverEmails() as $subscriptions){
            $this->receiver_email = $subscriptions->getEmail();
            $this->execute();
        }
    }

}
