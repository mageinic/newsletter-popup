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
    'mage/translate'
], function ($) {
    'use strict';

    return function (config) {
        $(document).ready(function () {
            var customUrl = config.url;
            var emailValue = $('#customer-email').val();

            $("#newsletter-popup .submit").click(function () {
                if ($('#newsletter-popup').valid()) {
                    ajaxNewsletterSubscribe(customUrl, emailValue);
                }
            });
        });
    }

    /**
     * Perform Ajax Newsletter Subscribe.
     *
     * @param {string} customUrl
     * @param {string} emailValue
     */
    function ajaxNewsletterSubscribe(customUrl, emailValue) {
        var html = '<div class="mage-error">' + $.mage.__(data['message']) + '</div>';
        $.ajax({
            url: customUrl,
            type: "POST",
            dataType: 'json',
            data: {email: emailValue},
            showLoader: true,
            success: function (data) {
                if (data['success'] === false) {
                    $(html).insertAfter('#customer-email');
                } else {
                    $("#mageinic-popup-modal").modal("closeModal");
                    setTimeout(function () {
                        $("#mageinic-popup-modal").modal("closeModal");
                    }, 1000);
                    $('.newsletter-popup').modal('hide');
                }
            },
            error: function (request, error) {
                console.log(error);
            }
        })
    }
});
