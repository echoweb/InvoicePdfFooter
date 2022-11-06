<?php

declare(strict_types=1);

namespace EchoWeb\InvoicePdfFooter\Setup\Patch\Data;

use Magento\Cms\Api\BlockRepositoryInterface;
use Magento\Cms\Api\Data\BlockInterfaceFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

/**
 * Class CreateInvoicePdfFooter
 */
class CreateInvoicePdfFooter implements DataPatchInterface
{
    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;

    /**
     * @var BlockRepositoryInterface
     */
    private $blockRepository;

    /**
     * @var BlockInterfaceFactory
     */
    private $blockFactory;

    /**
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param BlockRepositoryInterface $blockRepository
     * @param BlockInterfaceFactory $blockFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        BlockRepositoryInterface $blockRepository,
        BlockInterfaceFactory $blockFactory
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->blockRepository = $blockRepository;
        $this->blockFactory = $blockFactory;
    }

    /**
     * @return CreateInvoicePdfFooter|void
     * @throws LocalizedException
     */
    public function apply()
    {
        $newCmsStaticBlock = [
            'title' => 'Sales Invoice PDF Footer Cms Block',
            'identifier' => 'sales_invoice_pdf_cms_block',
            'content' => 'Thank you for buying from us',
            'is_active' => 1
        ];

        $this->moduleDataSetup->startSetup();

        /** @var \Magento\Cms\Model\Block $block */
        $block = $this->blockFactory->create();
        $block->setData($newCmsStaticBlock);
        $this->blockRepository->save($block);

        $this->moduleDataSetup->endSetup();
    }

    /** {@inheritdoc} */
    public static function getDependencies(): array
    {
        return [];
    }

    /** {@inheritdoc} */
    public function getAliases(): array
    {
        return [];
    }
}
