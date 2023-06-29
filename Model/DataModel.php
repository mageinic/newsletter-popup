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
 * @package MageINIC_<ModuleName>
 * @copyright Copyright (c) 2023. MageINIC (https://www.mageinic.com/)
 * @license https://opensource.org/licenses/gpl-3.0.html GNU General Public License,version 3 (GPL-3.0)
 * @author MageINIC <support@mageinic.com>
 */
namespace MageINIC\NewsletterPopup\Model;

use Magento\Customer\Helper\View;
use Magento\Customer\Model\Session;

/**
 * class Newsletter Popup Data Model
 */
class DataModel
{
    /**
     * @var Session
     */
    protected Session $_customerSession;

    /**
     * @var View
     */
    protected View $_customerViewHelper;

    /**
     * @var array
     */
    private array $postData = [];

    /**
     * DataModel Constructor
     *
     * @param Session $customerSession
     * @param View $customerViewHelper
     */
    public function __construct(
        Session $customerSession,
        View    $customerViewHelper
    ) {
        $this->_customerSession = $customerSession;
        $this->_customerViewHelper = $customerViewHelper;
    }

    /**
     * Get Username
     *
     * @return string
     */
    public function getUserName(): string
    {
        if (!$this->_customerSession->isLoggedIn()) {
            return '';
        }
        $customer = $this->_customerSession->getCustomerDataObject();
        return trim($this->_customerViewHelper->getCustomerName($customer));
    }

    /**
     * Get User email
     *
     * @return string
     */
    public function getUserEmail(): string
    {
        if (!$this->_customerSession->isLoggedIn()) {
            return '';
        }
        $customer = $this->_customerSession->getCustomerDataObject();
        return $customer->getEmail();
    }

    /**
     * Get value from POST by key
     *
     * @param string $key
     * @return string
     */
    public function getPostValue(string $key): string
    {
        if (isset($this->postData[$key])) {
            return (string) $this->postData[$key];
        }
        return '';
    }
}
