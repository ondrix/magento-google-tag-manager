<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Model\DataLayer\Impressions;

use DK\GoogleTagManager\Api\Data\DataLayerInterface;
use DK\GoogleTagManager\Model\DataLayer\Dto;
use DK\GoogleTagManager\Model\DataLayer\Generator\Impression;
use Magento\Catalog\Model\Product as ProductEntity;
use Magento\Store\Model\StoreManagerInterface;

class ProductRelatedView implements DataLayerInterface
{
    public const CODE = 'product-related-view';

    private const RELATED = 'Related Products';

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var Impression
     */
    private $impressionGenerator;

    public function __construct(Impression $impressionGenerator, StoreManagerInterface $storeManager)
    {
        $this->storeManager = $storeManager;
        $this->impressionGenerator = $impressionGenerator;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return static::CODE;
    }

    /**
     * {@inheritdoc}
     */
    public function getLayer()
    {
        /** @var ProductEntity $product */
        $product = $this->productHandler->getProduct();

        $relatedProducts = $product->getRelatedProducts();

        if (0 === \count($relatedProducts)) {
            return [];
        }

        $impressionProducts = [];
        foreach ($relatedProducts as $relatedProduct) {
            $impressionProducts[] = $this->impressionGenerator->generate($relatedProduct, self::RELATED);
        }

        $impression = new Dto\Impression\Impression();
        $impression->currencyCode = $this->storeManager->getStore()->getCurrentCurrency()->getCode();
        $impression->impressions = $impressionProducts;

        $ecommerce = new Dto\Impression\Ecommerce();
        $ecommerce->ecommerce = $impression;

        return $ecommerce;
    }
}