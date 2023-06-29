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
define([
    'jquery',
    'Magento_Ui/js/modal/modal',
    'jquery/jquery.cookie'
], function ($, modal) {
    'use strict';

    return function (config) {
        var options = {
            type: 'popup',
            responsive: true,
            innerScroll: true,
            modalClass: 'newsletter-popup',
            clickableOverlay: false,
            buttons: []
        };
        var popup = modal(options, $('#popup-modal'));
        $(document).ready(function () {
            var cookieVal = getCookie("OfferPOP");
            if (cookieVal !== 'True') {
                var date = new Date();
                var hours = config.hours;
                date.setTime(date.getTime() + (hours * 3600 * 1000));
                $.cookie("OfferPOP", "True", {expires: date});
                $("#popup-modal").modal("openModal");
                $('.main-news-popup').show();
            }
        });
    }
});

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) === ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) === 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}
