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

namespace MageINIC\NewsletterPopup\Api;

use Exception;
use MageINIC\NewsletterPopup\Api\Data\SubscriberInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use MageINIC\NewsletterPopup\Api\Data\SubscribersSearchResultsInterface;
use Magento\Framework\Exception\LocalizedException;

/**
 * Interface SubscriberRepositoryInterface
 * @api
 */
interface SubscriberRepositoryInterface
{
    /**
     * Subscribe api
     *
     * @param \MageINIC\NewsletterPopup\Api\Data\SubscriberInterface $subscriber
     * @return \MageINIC\NewsletterPopup\Api\Data\SubscriberInterface
     * @throws LocalizedException
     */
    public function postSubscriber(SubscriberInterface $subscriber): SubscriberInterface;

    /**
     * Unsubscribe api
     *
     * @param int $id
     * @param string $confirmationCode
     * @return \MageINIC\NewsletterPopup\Api\Data\SubscriberInterface
     * @throws LocalizedException
     */
    public function postUnsubscribe(int $id, string $confirmationCode);

    /**
     * POST Confirm Subscribe
     *
     * @param int $id
     * @param string $confirmationCode
     * @return \MageINIC\NewsletterPopup\Api\Data\SubscriberInterface
     * @throws Exception
     */
    public function postConfirm(int $id, string $confirmationCode);

    /**
     * Retrieve Subscriber matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \MageINIC\NewsletterPopup\Api\Data\SubscribersSearchResultsInterface
     * @throws LocalizedException
     */
    public function getList(SearchCriteriaInterface $searchCriteria);
}
