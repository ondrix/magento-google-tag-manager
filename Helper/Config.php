<?php

declare(strict_types=1);

namespace DK\GoogleTagManager\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\Store;

class Config extends AbstractHelper
{
    const XML_PATH_ACTIVE = 'google/googletagmanager/active';
    const XML_PATH_ACCOUNT = 'google/googletagmanager/account';

    /**
     * @param null $store
     *
     * @return bool
     */
    public function isGoogleTagManagerAvailable($store = null): bool
    {
        $account = $this->getAccount($store);

        return $account && $this->scopeConfig->isSetFlag(
            static::XML_PATH_ACTIVE,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }

    /**
     * @param null|string|bool|int|Store $store
     *
     * @return null|string
     */
    public function getAccount($store = null): ?string
    {
        return $this->scopeConfig->getValue(
            static::XML_PATH_ACCOUNT,
            ScopeInterface::SCOPE_STORE,
            $store
        );
    }
}