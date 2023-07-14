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

namespace MageINIC\NewsletterPopup\Api\Data;

/**
 * Interface SubscriberInterface
 * @api
 */
interface SubscriberInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    public const STORE_ID = 'store_id';
    public const SUBSCRIBER_ID = 'subscriber_id';
    public const SUBSCRIBER_EMAIL = 'subscriber_email';
    public const SUBSCRIBER_STATUS = 'subscriber_status';
    public const SUBSCRIBER_CHANGE_STATUS_AT = 'change_status_at';
    public const SUBSCRIBER_CONFORMATION_CODE = 'subscriber_confirm_code';
    /**#@-*/

    /**
     * Retrieve Subscriber Id
     *
     * @return int
     */
    public function getSubscriberId(): int;

    /**
     * Set Subscriber Id
     *
     * @param int $subscriberId
     * @return $this
     */
    public function setSubscriberId(int $subscriberId): SubscriberInterface;

    /**
     * Retrieve Store Id
     *
     * @return int
     */
    public function getStoreId(): int;

    /**
     * Set Store Id
     *
     * @param int $storeId
     * @return $this
     */
    public function setStoreId(int $storeId): SubscriberInterface;

    /**
     * Retrieve Subscriber Email
     *
     * @return string
     */
    public function getSubscriberEmail(): string;

    /**
     * Set Subscriber Email
     *
     * @param string $email
     * @return $this
     */
    public function setSubscriberEmail(string $email): SubscriberInterface;

    /**
     * Retrieve Subscriber Status
     *
     * @return int
     */
    public function getSubscriberStatus(): int;

    /**
     * Set Subscriber Status
     *
     * @param int $status
     * @return $this
     */
    public function setSubscriberStatus(int $status): SubscriberInterface;

    /**
     * Retrieve Subscriber Change Status At
     *
     * @return string
     */
    public function getChangeStatusAt(): string;

    /**
     * Set Subscriber Change Status At
     *
     * @param string $datetime
     * @return $this
     */
    public function setChangeStatusAt(string $datetime): SubscriberInterface;

    /**
     * Retrieve Subscriber Confirm Code
     *
     * @return string
     */
    public function getSubscriberConfirmCode(): string;

    /**
     * Set Subscriber Confirm Code
     *
     * @param string $confirmCode
     * @return $this
     */
    public function setSubscriberConfirmCode(string $confirmCode): SubscriberInterface;
}
