<?php

declare(strict_types=1);

namespace EchoWeb\InvoicePdfFooter\Plugin\Sales\Model\Order\Pdf;

use Magento\Cms\Block\Block;
use Magento\Framework\View\LayoutInterface;
use Magento\Sales\Model\Order\Pdf\Invoice as Invoice;
use Psr\Log\LoggerInterface;
use Zend_Pdf;
use Zend_Pdf_Page;

/**
 * Plugin to add cms block to footer of invoice PDF
 */
class InvoicePlugin
{
    /**
     * @var LayoutInterface
     */
    private $layout;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * InvoicePlugin constructor.
     *
     * @param LayoutInterface $layout
     * @param LoggerInterface $logger
     */
    public function __construct(
        LayoutInterface $layout,
        LoggerInterface $logger
    ) {
        $this->layout = $layout;
        $this->logger = $logger;
    }

    /**
     * after plugin to add extra cms block to pdf
     *
     * @param Invoice $subject
     * @param Zend_Pdf $result
     * @return Zend_Pdf
     */
    public function afterGetPdf(Invoice $subject, Zend_Pdf $result): Zend_Pdf
    {
        try {
            $lastPage = end($result->pages);
            $this->addInvoicePdfFooterCmsBlock($subject, $lastPage);
        } catch (\Exception $e) {
            $this->logger->critical($e);
        }

        return $result;
    }

    /**
     * Get the cms block content, add to pdf
     *
     * @param Invoice $invoice
     * @param Zend_Pdf_Page $page
     * @return void
     */
    private function addInvoicePdfFooterCmsBlock(Invoice $invoice, Zend_Pdf_Page $page): void
    {
        try {
            $page->drawText(
                $this->layout
                    ->createBlock(Block::class)
                    ->setBlockId('sales_invoice_pdf_cms_block')->toHtml(),
                250,
                $invoice->y - 50,
                'UTF-8'
            );
        } catch (\Exception $e) {
            $this->logger->critical($e);
        }
    }
}
