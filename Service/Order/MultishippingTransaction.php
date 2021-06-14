<?php
/**
 * Copyright Magmodules.eu. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Mollie\Multishipping\Service\Order;

use Magento\Framework\App\Helper\Context;
use Magento\Framework\UrlInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Mollie\Payment\Config;
use Mollie\Payment\Model\Adminhtml\Source\WebhookUrlOptions;

class MultishippingTransaction
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    public function __construct(
        Config $config,
        Context $context
    ) {
        $this->config = $config;
        $this->urlBuilder = $context->getUrlBuilder();
    }

    /**
     * @param OrderInterface[] $orders
     * @param string $paymentToken
     * @throws \Exception
     * @return string
     */
    public function getRedirectUrl(array $orders, string $paymentToken): string
    {
        if (!$orders) {
            throw new \Exception('The provided order array is empty');
        }

        $firstOrder = reset($orders);
        $storeId = $firstOrder->getStoreId();

        $orderIds = array_map( function (OrderInterface $order) { return $order->getId(); }, $orders);
        $parameters = http_build_query([
            'order_ids' => $orderIds,
            'payment_token' => $paymentToken,
            'utm_nooverride' => 1,
        ]);

        $this->urlBuilder->setScope($storeId);
        return $this->urlBuilder->getUrl(
            'mollie/checkout/process/',
            ['_query' => $parameters]
        );
    }

    /**
     * @param null|int|string $storeId
     * @return string
     */
    public function getWebhookUrl($storeId = null)
    {
        if ($this->config->isProductionMode($storeId) ||
            $this->config->useWebhooks($storeId) == WebhookUrlOptions::ENABLED) {
            return $this->urlBuilder->getUrl('mollie/checkout/webhook/', ['_query' => 'isAjax=1']);
        }

        if ($this->config->useWebhooks($storeId) == WebhookUrlOptions::DISABLED) {
            return '';
        }

        return $this->config->customWebhookUrl($storeId);
    }
}
