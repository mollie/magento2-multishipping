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
use Magento\TestFramework\ObjectManager;
use Magento\TestFramework\TestCase\AbstractController;
use Mollie\Payment\Model\Mollie;
use Mollie\Payment\Service\Mollie\GetMollieStatusResult;
use Mollie\Payment\Service\Mollie\ProcessTransaction;
use Mollie\Payment\Service\Mollie\ValidateProcessRequest;

class ProcessTest extends AbstractController
{
    /**
     * @magentoDataFixture Magento/Sales/_files/order_list.php
     * @return void
     */
    public function testRedirectsToSuccessPage()
    {
        $order1 = $this->loadOrderById('100000002');
        $order1Id = $order1->getEntityId();
        $order2 = $this->loadOrderById('100000003');
        $order2Id = $order2->getEntityId();

        $this->_objectManager->addSharedInstance(new class extends ProcessTransaction {
            public function __construct() {}

            public function execute(int $orderId, ?string $transactionId, string $type = 'webhook'): GetMollieStatusResult
            {
                return ObjectManager::getInstance()->create(GetMollieStatusResult::class, [
                    'status' => 'paid',
                    'method' => 'ideal',
                ]);
            }
        }, ProcessTransaction::class);

        $this->_objectManager->addSharedInstance(new class($order1Id, $order2Id) extends ValidateProcessRequest {

            private $order1Id;
            private $order2Id;

            public function __construct($order1Id, $order2Id) {
                $this->order1Id = $order1Id;
                $this->order2Id = $order2Id;
            }

            public function execute(): array
            {
                return [$this->order1Id => 'abc', $this->order2Id => 'def'];
            }
        }, ValidateProcessRequest::class);

        $this->dispatch('mollie/checkout/process?order_ids[]=' . $order1Id . '&order_ids[]=' . $order2Id . '&payment_tokens[]=abc&payment_tokens[]=def');

        $this->assertRedirect($this->stringContains('multishipping/checkout/success?utm_nooverride=1'));
    }

    /**
     * @param $orderId
     * @return \Magento\Sales\Model\Order
     */
    private function loadOrderById($orderId)
    {
        $repository = ObjectManager::getInstance()->get(OrderRepositoryInterface::class);
        $builder = ObjectManager::getInstance()->create(SearchCriteriaBuilder::class);
        $searchCriteria = $builder->addFilter('increment_id', $orderId, 'eq')->create();

        $orderList = $repository->getList($searchCriteria)->getItems();

        return array_shift($orderList);
    }
}
