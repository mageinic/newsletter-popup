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

use Magento\Config\Model\Config\Backend\Image;
use Magento\Framework\Exception\FileSystemException;
use Magento\Framework\Exception\LocalizedException;

/**
 * Block Offer Image for upload offer Image
 */
class OfferImage extends Image
{
    /**
     * The tail part of directory path for uploading
     */
    public const UPLOAD_DIR = 'mageINIC/newsletter';

    /**
     * @var int
     */
    protected $_maxFileSize = 5120;

    /**
     * Return path to directory for upload file
     *
     * @return string
     */
    protected function _getUploadDir(): string
    {
        return $this->_mediaDirectory->getAbsolutePath(self::UPLOAD_DIR);
    }

    /**
     * Makes a decision about whether to add info about the scope.
     *
     * @return boolean
     */
    protected function _addWhetherScopeInfo(): bool
    {
        return false;
    }

    /**
     * Getter for allowed extensions of uploaded files.
     *
     * @return string[]
     */
    protected function _getAllowedExtensions(): array
    {
        return ['jpg', 'jpeg', 'gif', 'png', 'svg'];
    }

    /**
     * Save uploaded file before saving config value
     *
     * @return $this
     * @throws FileSystemException
     * @throws LocalizedException
     */
    public function beforeSave(): OfferImage
    {
        $value = $this->getValue();
        $deleteFlag = is_array($value) && !empty($value['delete']);
        if (is_array($value)
            && $this->isTmpFileAvailable($value)
            && $this->getUploadedImageName($value)
        ) {
            $fileTmpName = $this->getFileData();

            if ($this->getOldValue() && ($fileTmpName || $deleteFlag)) {
                $this->_mediaDirectory->delete(self::UPLOAD_DIR . '/' . $this->getOldValue());
            }
        }

        return parent::beforeSave();
    }

    /**
     * Check if temporary file is available for new image upload.
     *
     * @param array $value
     * @return bool
     */
    private function isTmpFileAvailable(array $value): bool
    {
        return isset($value[0]['tmp_name']);
    }

    /**
     * Gets image name from $value array.
     *
     * @param array $value
     * @return string
     */
    private function getUploadedImageName(array $value): string
    {
        if (isset($value[0]['name'])) {
            return $value[0]['name'];
        }
        return '';
    }
}
