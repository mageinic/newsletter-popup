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

namespace MageINIC\NewsletterPopup\Setup\Patch\Data;

use Magento\Cms\Api\BlockRepositoryInterface as BlockRepository;
use Magento\Cms\Api\Data\BlockInterface;
use Magento\Cms\Model\BlockFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Setup\ModuleDataSetupInterface as ModuleData;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Store\Model\Store;

/**
 * Patch Data to Create Cms Blocks
 */
class DefaultCmsBlocks implements DataPatchInterface
{
    /**
     * Store id field name of cms Block
     */
    public const STORE = 'store_id';

    /**
     * @var ModuleData
     */
    private ModuleData $moduleDataSetup;

    /**
     * @var BlockFactory
     */
    private BlockFactory $blockFactory;

    /**
     * @var BlockRepository
     */
    protected BlockRepository $blockRepository;

    /**
     * DefaultContentBlocks constructor.
     *
     * @param ModuleData $moduleDataSetup
     * @param BlockFactory $blockFactory
     * @param BlockRepository $blockRepository
     */
    public function __construct(
        ModuleData      $moduleDataSetup,
        BlockFactory    $blockFactory,
        BlockRepository $blockRepository
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->blockFactory = $blockFactory;
        $this->blockRepository = $blockRepository;
    }

    /**
     * @inheritdoc
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * Provide data to create CMS Blocks.
     *
     * @return array[]
     */
    protected function getData(): array
    {
        return [
            [
                BlockInterface::TITLE      => 'Newsletter Popup Content',
                BlockInterface::IDENTIFIER => 'newsletter-popup-content',
                BlockInterface::CONTENT    => 'Newsletter Popup Content',
                BlockInterface::IS_ACTIVE  => true,
                self::STORE                => Store::DEFAULT_STORE_ID
            ],
            [
                BlockInterface::TITLE      => 'Newsletter Popup Form',
                BlockInterface::IDENTIFIER => 'front-content-form',
                BlockInterface::CONTENT    => '{{block class="MageINIC\NewsletterPopup\Block\NewsletterPopup"
                template="MageINIC_NewsletterPopup::newsletter-popup.phtml"}}',
                BlockInterface::IS_ACTIVE  => true,
                self::STORE                => Store::DEFAULT_STORE_ID
            ],
            [
                BlockInterface::TITLE      => 'Offer Popup Content',
                BlockInterface::IDENTIFIER => 'offer-popup',
                BlockInterface::CONTENT    => '{{block class="MageINIC\NewsletterPopup\Block\NewsletterPopup"
                template="MageINIC_NewsletterPopup::offer-template.phtml"}}',
                BlockInterface::IS_ACTIVE  => true,
                self::STORE                => Store::DEFAULT_STORE_ID
            ]
        ];
    }

    /**
     * Do Upgrade
     *
     * @return void
     * @throws LocalizedException
     */
    public function apply(): void
    {
        $this->moduleDataSetup->startSetup();

        /** @var BlockInterface $item */
        foreach ($this->getData() as $item) {
            $model = $this->blockFactory->create();
            $model->setData($item);
            $this->blockRepository->save($model);
        }

        $this->moduleDataSetup->endSetup();
    }

    /**
     * Delete the Block
     *
     * @return void
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function revert(): void
    {
        foreach ($this->getData() as $item) {
            $block = $this->blockFactory->create()
                ->load($item[BlockInterface::IDENTIFIER], BlockInterface::IDENTIFIER);
            if ($block->getId()) {
                $this->blockRepository->deleteById($block->getId());
            }
        }
    }

    /**
     * @inheritdoc
     */
    public function getAliases(): array
    {
        return [];
    }
}
