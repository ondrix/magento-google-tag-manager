<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Controller\Impression;

use DK\GoogleTagManager\Factory\ClickImpressionHandlerFactory;
use DK\GoogleTagManager\Model\DataLayer\Impressions\ClickImpressionView;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Psr\Log\LoggerInterface;

class View extends Action
{
    /**
     * @var RedirectInterface
     */
    private $redirect;

    /**
     * @var ClickImpressionHandlerFactory
     */
    private $clickImpressionHandlerFactory;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var ClickImpressionView
     */
    private $clickImpressionView;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        Context $context,
        RedirectInterface $redirect,
        ClickImpressionHandlerFactory $clickImpressionHandlerFactory,
        ProductRepositoryInterface $productRepository,
        ClickImpressionView $clickImpressionView,
        SerializerInterface $serializer,
        LoggerInterface $logger
    ) {
        parent::__construct($context);
        $this->redirect = $redirect;
        $this->clickImpressionHandlerFactory = $clickImpressionHandlerFactory;
        $this->productRepository = $productRepository;
        $this->clickImpressionView = $clickImpressionView;
        $this->serializer = $serializer;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        if ($this->getRequest()->isAjax()) {
            $currentUrl = $this->getRequest()->getParam('currentUrl');
            $refererUrl = $this->getRequest()->getParam('refererUrl');

            if (null === $refererUrl || $refererUrl === $currentUrl) {
                return null;
            }

            $layer = null;
            $productId = (int) $this->getRequest()->getParam('id');
            if ($productId) {
                try {
                    $product = $this->productRepository->getById($productId);

                    $handlerImpressionClick = $this->clickImpressionHandlerFactory->create($refererUrl);
                    $handlerImpressionClick->handle($product);

                    return $this->getResponse()->representJson(
                        $this->serializer->serialize($this->clickImpressionView->getLayer())
                    );
                } catch (\Exception $e) {
                    $this->logger->critical($e->getMessage());
                }
            }
        }

        return null;
    }
}
