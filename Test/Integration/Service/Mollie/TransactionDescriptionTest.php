<?php
/*
 * Copyright Magmodules.eu. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Mollie\Multishipping\Test\Integration\Service\Mollie;

use Mollie\Multishipping\Service\Mollie\TransactionDescription;
use Mollie\Multishipping\Config;
use Mollie\Payment\Test\Integration\IntegrationTestCase;

class TransactionDescriptionTest extends IntegrationTestCase
{
    public function returnsTheCorrectDescriptionForMultishippingTransactionsProvider()
    {
        return [
            ['', 'My Test Store order'],
            ['{storename}', 'My Test Store'],
            ['{storename} order', 'My Test Store order'],
            ['Thank you for order at {storename}', 'Thank you for order at My Test Store'],
        ];
    }

    /**
     * @magentoConfigFixture current_store general/store_information/name My Test Store
     * @dataProvider returnsTheCorrectDescriptionForMultishippingTransactionsProvider
     */
    public function testReturnsTheCorrectDescriptionForMultishippingTransactions($description, $expected)
    {
        $configMock = $this->createMock(Config::class);
        $configMock->method('getMultishippingDescription')->willReturn($description);

        /** @var TransactionDescription $instance */
        $instance = $this->objectManager->create(TransactionDescription::class, [
            'config' => $configMock,
        ]);

        $result = $instance->forMultishippingTransaction(1);

        $this->assertSame($expected, $result);
    }
}
