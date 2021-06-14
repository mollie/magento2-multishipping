<?php
/*
 * Copyright Magmodules.eu. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Mollie\Multishipping\Observer\MollieCheckoutSuccessRedirect;

use Magento\Framework\App\Response\RedirectInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Multishipping\Model\Checkout\Type\Multishipping\State;

class RedirectToMultishippingSuccessPage implements ObserverInterface
{
    /**
     * @var State
     */
    private $state;

    /**
     * @var RedirectInterface
     */
    private $redirect;

    public function __construct(
        State $state,
        RedirectInterface $redirect
    ) {
        $this->state = $state;
        $this->redirect = $redirect;
    }

    public function execute(Observer $observer)
    {
        if (count($observer->getData('order_ids')) === 1) {
            return;
        }

        $this->state->setCompleteStep(State::STEP_OVERVIEW);
        $this->state->setActiveStep(State::STEP_SUCCESS);

        $this->redirect->redirect($observer->getData('response'), 'multishipping/checkout/success?utm_nooverride=1');
    }
}
