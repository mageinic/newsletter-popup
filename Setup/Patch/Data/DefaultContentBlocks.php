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
namespace MageINIC\NewsletterPopup\Setup\Patch\Data;

use Magento\Cms\Model\BlockFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface as ModuleData;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Store\Model\Store;

/**
 * Patch Data Create DefaultContentBlocks
 */
class DefaultContentBlocks implements DataPatchInterface
{
    /**
     * @var BlockFactory
     */
    private BlockFactory $blockFactory;

    /**
     * @var ModuleData
     */
    private ModuleData $moduleDataSetup;

    /**
     * DefaultContentBlocks constructor.
     *
     * @param BlockFactory $blockFactory
     * @param ModuleData $moduleDataSetup
     */
    public function __construct(
        BlockFactory $blockFactory,
        ModuleData   $moduleDataSetup
    ) {
        $this->blockFactory = $blockFactory;
        $this->moduleDataSetup = $moduleDataSetup;
    }

    /**
     * Do Upgrade
     *
     * @return void
     */
    public function apply(): void
    {
        $this->moduleDataSetup->startSetup();

        $block = $this->blockFactory->create();
        $block->setTitle('Newsletter Popup Content')
            ->setIdentifier('newsletter-popup-content')
            ->setIsActive(true)
            ->setStores(Store::DEFAULT_STORE_ID)
            ->setContent('Newsletter popup content')
            ->save();

        $block = $this->blockFactory->create();
        $block->setTitle('Newsletter Popup Form')
            ->setIdentifier('front-content-form')
            ->setIsActive(true)
            ->setStores(Store::DEFAULT_STORE_ID)
            ->setContent(
                '{{block class="MageINIC\NewsletterPopup\Block\NewsletterPopup"
                template="MageINIC_NewsletterPopup::newsletter-popup.phtml"}}'
            )->save();

        $block = $this->blockFactory->create();
        $block->setTitle('Offer Popup Content')
            ->setIdentifier('offer-popup')
            ->setIsActive(true)
            ->setStores(Store::DEFAULT_STORE_ID)
            ->setContent(
                '{{block class="MageINIC\NewsletterPopup\Block\NewsletterPopup"
                template="MageINIC_NewsletterPopup::offer-template.phtml"}}'
            )->save();

        $this->moduleDataSetup->endSetup();
    }

    /**
     * @inheritdoc
     */
    public function getAliases(): array
    {
        return [];
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies(): array
    {
        return [];
    }
}
