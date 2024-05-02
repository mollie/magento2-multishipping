<?php
/*
 *
 *  * Copyright Magmodules.eu. All rights reserved.
 *  * See COPYING.txt for license details.
 *
 */

declare(strict_types=1);

namespace Mollie\Multishipping\Test\Integration\Xml;

use Magento\Multishipping\Model\Checkout\Type\Multishipping\PlaceOrderPool;
use Mollie\Payment\Service\Mollie\PaymentMethods;
use Mollie\Payment\Test\Integration\IntegrationTestCase;

class DiXmlTest extends IntegrationTestCase
{
    /**
     * @magentoAppArea frontend
     * @return void
     */
    public function testPlaceOrderPoolHasAllMollieMethods(): void
    {
        /** @var PlaceOrderPool $instance */
        $instance = $this->objectManager->get(PlaceOrderPool::class);

        foreach ($this->objectManager->get(PaymentMethods::class)->getCodes() as $code) {
            $this->assertNotNull(
                $instance->get($code),
                sprintf('An instance should be returned for "%s"', $code)
            );
        }
    }
}
