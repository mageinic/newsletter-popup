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

namespace MageINIC\NewsletterPopup\Block\System\Config;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

/**
 * Block Guest User Newsletter subscription system notice
 */
class Guest extends Field
{
    /**
     * @inheritdoc
     */
    protected function _getElementHtml(AbstractElement $element): string
    {
        $text = __("Enable Newsletter Subscription for Guest user Navigate to ");
        $txt = __("Stores") . ' > ' . __("Configuration") . ' > '
            . __("Customer") . ' > ' . __("Newsletter") . ' > '
            . __("Subscription Options") . ' > ' . __("Allow Guest Subscription");

        $html = '<div class="notices-wrapper">';
        $html .= '<div class="messages">';
        $html .= '<div class="message" style="margin-top: 10px;">';
        $html .= '<span>' . $text . '<br /><strong>' . $txt . '</strong></span>';
        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }
}
