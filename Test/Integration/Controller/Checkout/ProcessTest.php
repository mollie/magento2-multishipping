<?php
/*
 * Copyright Magmodules.eu. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Mollie\Multishipping\Test\Integration\Controller\Checkout;

use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Message\MessageInterface;
use Magento\Sales\Api\Data\OrderInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\TestFramework\TestCase\AbstractController;
use Mollie\Payment\Model\Mollie;

class ProcessTest extends AbstractController
{
    public function testRedirectsToSuccessPage()
    {
        $mollieModel = $this->createMock(Mollie::class);
        $mollieModel->method('processTransaction')->willReturn(['success' => true]);

        $this->_objectManager->addSharedInstance($mollieModel, Mollie::class);

        $this->dispatch('mollie/checkout/process?order_ids[]=123&order_ids[]=456');

        $this->assertRedirect($this->stringContains('multishipping/checkout/success?utm_nooverride=1'));
    }
}
