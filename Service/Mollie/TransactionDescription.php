<?php
/*
 * Copyright Magmodules.eu. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Mollie\Multishipping\Service\Mollie;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\Information;
use Magento\Store\Model\ScopeInterface;
use Mollie\Multishipping\Config;

class TransactionDescription
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(
        Config $config,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->config = $config;
        $this->scopeConfig = $scopeConfig;
    }

    public function forMultishippingTransaction($storeId = 0): string
    {
        $description = $this->config->getMultishippingDescription($storeId);

        if (!trim($description)) {
            $description = __('{storename} order');
        }

        $storeName = $this->scopeConfig->getValue(
            Information::XML_PATH_STORE_INFO_NAME,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );

        $replacements = [
            '{storename}' => $storeName,
        ];

        return str_replace(
            array_keys($replacements),
            array_values($replacements),
            $description
        );
    }
}
