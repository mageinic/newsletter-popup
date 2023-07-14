<?php
/**
 * MageINIC
 * Copyright (C) 2023. MageINIC <support@mageinic.com>
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
 * @copyright Copyright (c) 2023. MageINIC (https://www.mageinic.com/)
 * @license https://opensource.org/licenses/gpl-3.0.html GNU General Public License,version 3 (GPL-3.0)
 * @author MageINIC <support@mageinic.com>
 */

namespace MageINIC\NewsletterPopup\Model;

use Exception;
use MageINIC\NewsletterPopup\Api\Data\SubscriberInterface;
use MageINIC\NewsletterPopup\Api\SubscriberRepositoryInterface;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface as CollectionProcessor;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Validator\EmailAddress;
use MageINIC\NewsletterPopup\Api\Data\SubscribersSearchResultsInterfaceFactory as SubscribersSearchResults;
use Magento\Newsletter\Model\ResourceModel\Subscriber\CollectionFactory as Collection;
use Magento\Newsletter\Model\Subscriber as ModelSubscriber;

/**
 * @inheritdoc
 */
class SubscriberRepository implements SubscriberRepositoryInterface
{
    /**
     * @var ModelSubscriber
     */
    protected ModelSubscriber $subscriberModel;

    /**
     * @var EmailAddress
     */
    protected EmailAddress $emailValidator;

    /**
     * @var Collection
     */
    protected Collection $subscribersCollection;

    /**
     * @var CollectionProcessor
     */
    protected CollectionProcessor $collectionProcessor;

    /**
     * @var SubscribersSearchResults
     */
    private SubscribersSearchResults $searchResultsFactory;

    /**
     * SubscriberManagement Constructor.
     *
     * @param ModelSubscriber $subscriberModel
     * @param EmailAddress $emailValidator
     * @param Collection $subscribersCollection
     * @param CollectionProcessor $collectionProcessor
     * @param SubscribersSearchResults $searchResultsFactory
     */
    public function __construct(
        ModelSubscriber          $subscriberModel,
        EmailAddress             $emailValidator,
        Collection               $subscribersCollection,
        CollectionProcessor      $collectionProcessor,
        SubscribersSearchResults $searchResultsFactory,
    ) {
        $this->subscriberModel = $subscriberModel;
        $this->emailValidator = $emailValidator;
        $this->subscribersCollection = $subscribersCollection;
        $this->collectionProcessor = $collectionProcessor;
        $this->searchResultsFactory = $searchResultsFactory;
    }

    /**
     * @inheritdoc
     */
    public function postSubscriber(SubscriberInterface $subscriber): SubscriberInterface
    {
        $email = (string)$subscriber->getSubscriberEmail();
        $this->validateEmailFormat($email);
        $subscriberModel = $this->subscriberModel->loadByEmail($email);
        if ($subscriberModel->getId()
            && (int)$subscriberModel->getSubscriberStatus() === ModelSubscriber::STATUS_SUBSCRIBED
        ) {
            throw new LocalizedException(__('This email address is already subscribed.'));
        }
        $subscriberModel->subscribe($email);
        $subscriber->addData($subscriberModel->getData());

        return $subscriber;
    }

    /**
     * @inheritdoc
     */
    public function postUnsubscribe(int $id, string $confirmationCode)
    {
        $subscriber = $this->subscriberModel->load($id);
        $subscriber->setCheckCode($confirmationCode);
        $subscriber->unsubscribe();
        return $subscriber;
    }

    /**
     * @inheritdoc
     */
    public function postConfirm(int $id, string $confirmationCode)
    {
        $subscriber = $this->subscriberModel->load($id);
        if ($subscriber->getSubscriberId() && $subscriber->getSubscriberConfirmCode()) {
            if ($subscriber->confirm($confirmationCode)) {
                return $subscriber;
            } else {
                throw new Exception(__('This is an invalid subscription confirmation code.'));
            }
        } else {
            throw new Exception(__('This is an invalid subscription ID.'));
        }
    }

    /**
     * @inheritdoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        $collection = $this->subscribersCollection->create();
        $this->collectionProcessor->process($searchCriteria, $collection);
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($searchCriteria);

        /** @var SubscriberInterface[] $collection */
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * Validates the format of the email address
     *
     * @param string $email
     * @return void
     * @throws LocalizedException
     */
    protected function validateEmailFormat(string $email): void
    {
        if (!$this->emailValidator->isValid($email)) {
            throw new LocalizedException(__('Please enter a valid email address.'));
        }
    }
}
