<?php
/*
 * Copyright Magmodules.eu. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Mollie\Multishipping\Observer\CheckoutSubmitAllAfter;

use Magento\Framework\App\ResponseFactory;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Mollie\Multishipping\Service\CheckoutUrl;

class RedirectMolliePaymentToTheCheckoutUrl implements ObserverInterface
{
    /**
     * @var CheckoutUrl
     */
    private $checkoutUrl;

    /**
     * @var ResponseFactory
     */
    private $responseFactory;

    public function __construct(
        CheckoutUrl $checkoutUrl,
        ResponseFactory $responseFactory
    ) {
        $this->checkoutUrl = $checkoutUrl;
        $this->responseFactory = $responseFactory;
    }

    public function execute(Observer $observer)
    {
        if ($url = $this->checkoutUrl->getUrl()) {
            $response = $this->responseFactory->create();
            $response->setRedirect($url);
            $response->sendResponse();

            // phpcs:ignore
            exit;
        }
    }
}
