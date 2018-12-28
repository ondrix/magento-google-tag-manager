<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use DK\GoogleTagManager\Helper\Config;

class Gtm extends Template
{
    /**
     * @var Config $config
     */
    private $config;

    /**
     * Gtm constructor.
     *
     * @param Context $context
     * @param Config $config
     * @param array $data
     */
    public function __construct(
        Context $context,
        Config $config,
        array $data = []
    ) {
        $this->config = $config;
        parent::__construct($context, $data);
    }

    /**
     * @return Config
     */
    public function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * @inheritdoc
     */
    protected function _toHtml()
    {
        if (!$this->config->isGoogleTagManagerAvailable()) {
            return '';
        }

        return parent::_toHtml();
    }
}