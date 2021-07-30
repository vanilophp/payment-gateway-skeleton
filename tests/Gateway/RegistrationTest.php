<?php

declare(strict_types=1);

namespace {:name_space_root:}\Tests\Gateway;

use Vanilo\Payment\Contracts\PaymentGateway;
use Vanilo\Payment\PaymentGateways;
use {:name_space_root:}\XXXPaymentGateway;
use {:name_space_root:}\Tests\TestCase;

class RegistrationTest extends TestCase
{
    /** @test */
    public function the_gateway_is_registered_out_of_the_box_with_defaults()
    {
        $this->assertCount(2, PaymentGateways::ids());
        $this->assertContains(XXXPaymentGateway::DEFAULT_ID, PaymentGateways::ids());
    }

    /** @test */
    public function the_gateway_can_be_instantiated()
    {
        $gateway = PaymentGateways::make('{:payment_gateway_name|slug:}');

        $this->assertInstanceOf(PaymentGateway::class, $gateway);
        $this->assertInstanceOf(XXXPaymentGateway::class, $gateway);
    }
}
