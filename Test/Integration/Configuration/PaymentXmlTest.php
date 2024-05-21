<?php
/*
 *
 *  * Copyright Magmodules.eu. All rights reserved.
 *  * See COPYING.txt for license details.
 *
 */

declare(strict_types=1);

namespace Mollie\Multishipping\Test\Integration\Xml;

use Magento\Payment\Model\Config\Reader;
use Mollie\Payment\Service\Mollie\PaymentMethods;
use Mollie\Payment\Test\Integration\IntegrationTestCase;

class PaymentXmlTest extends IntegrationTestCase
{
    public function testHasConfigurationForAllApplicableMethods(): void
    {
        /** @var Reader $reader */
        $reader = $this->objectManager->get(Reader::class);

        $methods = $reader->read()['methods'];

        $allMethods = array_filter(
            $this->objectManager->get(PaymentMethods::class)->getCodes(),
            function (string $code) {
                return !in_array(
                    $code,
                    [
                        'mollie_methods_in3',
                        'mollie_methods_klarna',
                        'mollie_methods_klarnapaylater',
                        'mollie_methods_klarnapaynow',
                        'mollie_methods_klarnasliceit',
                        'mollie_methods_pointofsale',
                    ]
                );
            }
        );


        foreach ($allMethods as $code) {
            $this->assertArrayHasKey($code, $methods);

            $method = $methods[$code];
            $this->assertArrayHasKey('allow_multiple_address', $method);
            $this->assertEquals(
                '1',
                $method['allow_multiple_address'],
                sprintf('allow_multiple_address for method %s should be enabled', $code)
            );
        }
    }
}
