<?php
/*
 * Copyright Magmodules.eu. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Mollie\Multishipping;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    const GENERAL_MULTISHIPPING_ENABLED = 'payment/mollie_general/multishipping_enabled';
    const GENERAL_MULTISHIPPING_DESCRIPTION = 'payment/mollie_general/multishipping_description';

    /**
     * @var ScopeConfigInterface
     */
    private $config;

    public function __construct(
        ScopeConfigInterface $config
    ) {
        $this->config = $config;
    }

    /**
     * @param $path
     * @param null|int|string $storeId
     * @param string $scope
     * @return string
     */
    private function getPath($path, $storeId, $scope = ScopeInterface::SCOPE_STORE)
    {
        return $this->config->getValue($path, $scope, $storeId);
    }

    /**
     * @param $path
     * @param null|int|string $storeId
     * @param string $scope
     * @return bool
     */
    private function isSetFlag($path, $storeId, $scope = ScopeInterface::SCOPE_STORE)
    {
        return $this->config->isSetFlag($path, $scope, $storeId);
    }

    /**
     * @param null|int|string $storeId
     * @param string $scope
     * @return bool
     */
    public function isMultishippingEnabled($storeId = null, $scope = ScopeInterface::SCOPE_STORE): bool
    {
        return $this->isSetFlag(static::GENERAL_MULTISHIPPING_ENABLED, $storeId, $scope);
    }

    /**
     * @param null|int|string $storeId
     * @param string $scope
     * @return string|null
     */
    public function getMultishippingDescription($storeId = null, $scope = ScopeInterface::SCOPE_STORE)
    {
        return $this->getPath(static::GENERAL_MULTISHIPPING_DESCRIPTION, $storeId, $scope);
    }
}
