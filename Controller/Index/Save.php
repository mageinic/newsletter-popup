<?php
/**
 * MageINIC
 * Copyright (C) 2023 MageINIC <support@mageinic.com>
 *
 * NOTICE OF LICENSE
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see https://opensource.org/licenses/gpl-3.0.html.
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category MageINIC
 * @package MageINIC_NewsletterPopup
 * @copyright Copyright (c) 2023 MageINIC (https://www.mageinic.com/)
 * @license https://opensource.org/licenses/gpl-3.0.html GNU General Public License,version 3 (GPL-3.0)
 * @author MageINIC <support@mageinic.com>
 */
namespace MageINIC\NewsletterPopup\Controller\Index;

use Exception;
use Magento\Customer\Model\Customer;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Config\ScopeConfigInterface as ScopeConfig;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\Translate\Inline\ParserInterface;
use Magento\Newsletter\Model\Subscriber;
use Magento\Newsletter\Model\SubscriberFactory;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface as StoreManager;
use Magento\Customer\Model\Url;
use Magento\Framework\Validator\EmailAddress as EmailValidator;

/**
 * Controller Save
 */
class Save extends Action implements HttpPostActionInterface
{
    /**
     * @var SubscriberFactory
     */
    protected SubscriberFactory $subscriberFactory;

    /**
     * @var Subscriber
     */
    protected Subscriber $subscriber;

    /**
     * @var Customer
     */
    protected Customer $customerRepository;

    /**
     * @var StoreManager
     */
    protected StoreManager $storeManager;

    /**
     * @var ManagerInterface
     */
    protected $messageManager;

    /**
     * @var JsonFactory
     */
    protected JsonFactory $resultJsonFactory;

    /**
     * @var ParserInterface
     */
    protected ParserInterface $inlineParser;

    /**
     * @var RequestInterface
     */
    private RequestInterface $request;

    /**
     * @var Session
     */
    private Session $_customerSession;

    /**
     * @var ScopeConfig
     */
    private ScopeConfig $scopeConfig;

    /**
     * @var Url
     */
    private Url $url;

    /**
     * @var EmailValidator
     */
    private EmailValidator $emailValidator;

    /**
     * Save Constructor
     *
     * @param Context $context
     * @param SubscriberFactory $subscriberFactory
     * @param Subscriber $subscriber
     * @param Customer $customerRepository
     * @param StoreManager $storeManager
     * @param ManagerInterface $messageManager
     * @param JsonFactory $resultJsonFactory
     * @param ParserInterface $inlineParser
     * @param Session $customerSession
     * @param ScopeConfig $scopeConfig
     * @param RequestInterface $request
     * @param EmailValidator $emailValidator
     * @param Url $url
     */
    public function __construct(
        Context           $context,
        SubscriberFactory $subscriberFactory,
        Subscriber        $subscriber,
        Customer          $customerRepository,
        StoreManager      $storeManager,
        ManagerInterface  $messageManager,
        JsonFactory       $resultJsonFactory,
        ParserInterface   $inlineParser,
        Session           $customerSession,
        ScopeConfig       $scopeConfig,
        RequestInterface  $request,
        EmailValidator    $emailValidator,
        Url               $url
    ) {
        $this->subscriberFactory = $subscriberFactory;
        $this->subscriber = $subscriber;
        $this->customerRepository = $customerRepository;
        $this->storeManager = $storeManager;
        $this->messageManager = $messageManager;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->inlineParser = $inlineParser;
        $this->request = $request;
        $this->_customerSession = $customerSession;
        $this->scopeConfig = $scopeConfig;
        $this->url = $url;
        $this->emailValidator = $emailValidator;
        parent::__construct($context);
    }

    /**
     * Execute action based on request and return result
     *
     * @return Json|ResultInterface|void
     */
    public function execute()
    {
        if ($this->request->isXmlHttpRequest()) {
            $email = $this->getRequest()->getParam('email');
            $subscriber = $this->subscriberFactory->create()->loadByEmail($email);
            $resultJson = $this->resultJsonFactory->create();
            if ($this->emailValidator->isValid($email)) {
                if (!$subscriber->getSubscriberEmail()) {
                    $response = $this->newsLetterSubscribe($email);
                    $this->_actionFlag->set('', self::FLAG_NO_POST_DISPATCH, true);
                } else {
                    $this->messageManager->addErrorMessage(__("This email address is already subscribed."));
                    $response = ['error' => 'true'];
                }
            } else {
                $this->messageManager->addErrorMessage(__("Please enter a valid email address."));
                $response = ['error' => 'true'];
            }
            return $resultJson->setData($response);
        }
    }

    /**
     * Check Guest User Subscription
     *
     * @return bool
     */
    protected function guestUserSubscription(): bool
    {
        return ($this->scopeConfig->getValue(
            Subscriber::XML_PATH_ALLOW_GUEST_SUBSCRIBE_FLAG,
            ScopeInterface::SCOPE_STORE
        ) != 1 && !$this->_customerSession->isLoggedIn());
    }

    /**
     * Subscribe for NewsLetter
     *
     * @param string $email
     * @return array|string[]
     */
    public function newsLetterSubscribe(string $email): array
    {
        try {
            $currentWebsiteId = $this->storeManager->getStore()->getWebsiteId();
            $currentStoreId = $this->storeManager->getStore()->getId();
            $customer = $this->customerRepository->setWebsiteId($currentWebsiteId);
            $customer->loadByEmail($email);
            if ($customer->getId()) {
                $customerId = $customer->getId();
                $this->subscriber->setEmail($email);
                $this->subscriber->setCustomerId($customerId);
                $this->subscriber->setStoreId($currentStoreId);
                $this->subscriber->setSubscriberStatus('1');
                $this->subscriber->save();
                $this->messageManager->addSuccessMessage(__('Thank you for your subscription.'));
                $this->subscriber->sendConfirmationSuccessEmail();
            } elseif ($this->guestUserSubscription() != '') {
                $this->messageManager->addComplexErrorMessage(
                    'newsLetterMessage',
                    ['url' => $this->url->getRegisterUrl()]
                );
            } else {
                $model = $this->subscriberFactory->create();
                $model->subscribe($email);
                $this->messageManager->addSuccessMessage(__('Thank you for your subscription.'));
            }
            $this->inlineParser->processAjaxPost([$email]);
            $response = ['success' => 'true'];
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage(__($e->getMessage()));
            $response = ['error' => $e->getMessage()];
        }
        return $response;
    }
}
