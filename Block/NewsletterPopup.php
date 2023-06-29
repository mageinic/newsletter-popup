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
namespace MageINIC\NewsletterPopup\Block;

use MageINIC\NewsletterPopup\Model\DataModel as Data;
use Magento\Backend\Block\Template\Context;
use Magento\Framework\View\Element\Template;
use Magento\Store\Model\ScopeInterface;

/**
 * Block NewsletterPopup for configuration
 */
class NewsletterPopup extends Template
{
    /**
     * NewsletterPopupconfig path's
     */
    public const XML_PATH_POP_SELECT = 'pop/offer_config/pop_select';
    public const XML_PATH_POP_DISPLAY = 'pop/offer_config/pop_time';
    public const XML_PATH_OFFER_URL = 'pop/offer_config/offer_url';
    public const XML_PATH_OFFER_IMAGE = 'pop/offer_config/image_upload';
    public const SAVE_NEWSLETTER_PATH = 'newsletterpopup/index/save';

    /**
     * @var Data
     */
    private Data $helper;

    /**
     * NewsletterPopup constructor.
     *
     * @param Context $context
     * @param Data $helper
     */
    public function __construct(
        Context $context,
        Data    $helper
    ) {
        $this->helper = $helper;
        parent::__construct($context);
    }

    /**
     * Get Form Action
     *
     * @return string
     */
    public function getFormAction(): string
    {
        return $this->getUrl(self::SAVE_NEWSLETTER_PATH, ['_secure' => true]);
    }

    /**
     * Get Pop-up Select
     *
     * @return string
     */
    public function getPopUpSelect(): string
    {
        return $this->_scopeConfig->getValue(
            self::XML_PATH_POP_SELECT,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Display Time
     *
     * @return mixed
     */
    public function getDisplayTime(): mixed
    {
        $displayTime = $this->_scopeConfig->getValue(
            self::XML_PATH_POP_DISPLAY,
            ScopeInterface::SCOPE_STORE
        );
        if (!$displayTime) {
            $displayTime = 1;
        }
        return $displayTime;
    }

    /**
     * Get Offer Url
     *
     * @return string|null
     */
    public function getOfferUrl(): ?string
    {
        return $this->_scopeConfig->getValue(
            self::XML_PATH_OFFER_URL,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Offer Image path
     *
     * @return string|null
     */
    public function getOfferImage(): ?string
    {
        return $this->_scopeConfig->getValue(
            self::XML_PATH_OFFER_IMAGE,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get Offer Image Url
     *
     * @return string
     */
    public function getOfferImageUrl(): string
    {
        return $this->getBaseUrl() . 'pub/media/mageINIC/newsletter/' . $this->getOfferImage();
    }

    /**
     * Default Offer Image path
     *
     * @return string
     */
    public function getDefaultOfferImage(): string
    {
        return $this->getViewFileUrl(
            'MageINIC_NewsletterPopup::images/special-offer-banner.jpg'
        );
    }

    /**
     * Get User Email
     *
     * @return string
     */
    public function getUserEmail(): string
    {
        return $this->helper->getPostValue('email') ?: $this->helper->getUserEmail();
    }
}
