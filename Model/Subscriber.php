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

use MageINIC\NewsletterPopup\Api\Data\SubscriberInterface;
use Magento\Framework\Model\AbstractModel;

class Subscriber extends AbstractModel implements SubscriberInterface
{
    /**
     * Newsletter subscriber cache tag
     */
    public const CACHE_TAG = 'newsletter_s';

    /**
     * @var string
     */
    protected $_cacheTag = self::CACHE_TAG;

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'newsletter_subscriber';

    /**
     * Get identities
     *
     * @return array
     */
    public function getIdentities(): array
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * @inheritdoc
     */
    public function getSubscriberId():int
    {
        return $this->getData(self::SUBSCRIBER_ID);
    }

    /**
     * @inheritdoc
     */
    public function setSubscriberId(int $subscriberId): SubscriberInterface
    {
        return $this->setData(self::SUBSCRIBER_ID, $subscriberId);
    }

    /**
     * @inheritdoc
     */
    public function getStoreId(): int
    {
        return $this->getData(self::STORE_ID);
    }

    /**
     * @inheritdoc
     */
    public function setStoreId(int $storeId): SubscriberInterface
    {
        return $this->setData(self::STORE_ID, $storeId);
    }

    /**
     * @inheritdoc
     */
    public function getSubscriberEmail(): string
    {
        return $this->getData(self::SUBSCRIBER_EMAIL);
    }

    /**
     * @inheritdoc
     */
    public function setSubscriberEmail(string $email): SubscriberInterface
    {
        return $this->setData(self::SUBSCRIBER_EMAIL, $email);
    }

    /**
     * @inheritdoc
     */
    public function getSubscriberStatus(): int
    {
        return $this->getData(self::SUBSCRIBER_STATUS);
    }

    /**
     * @inheritdoc
     */
    public function setSubscriberStatus(int $status): SubscriberInterface
    {
        return $this->setData(self::SUBSCRIBER_STATUS, $status);
    }

    /**
     * @inheritdoc
     */
    public function getChangeStatusAt(): string
    {
        return $this->getData(self::SUBSCRIBER_CHANGE_STATUS_AT);
    }

    /**
     * @inheritdoc
     */
    public function setChangeStatusAt(string $datetime): SubscriberInterface
    {
        return $this->setData(self::SUBSCRIBER_CHANGE_STATUS_AT, $datetime);
    }

    /**
     * @inheritdoc
     */
    public function getSubscriberConfirmCode(): string
    {
        return $this->getData(self::SUBSCRIBER_CONFORMATION_CODE);
    }

    /**
     * @inheritdoc
     */
    public function setSubscriberConfirmCode(string $confirmCode): SubscriberInterface
    {
        return $this->setData(self::SUBSCRIBER_CONFORMATION_CODE, $confirmCode);
    }
}
