<?php

declare(strict_types=1);

namespace {:name_space_root:}\Tests\Gateway;

use Vanilo\Payment\PaymentGateways;
use {:name_space_root:}\Tests\TestCase;

class OmitRegistrationTest extends TestCase
{
    protected function setUp(): void
    {
        PaymentGateways::reset();
        parent::setUp();
    }

    /** @test */
    public function the_gateway_registration_can_be_disabled()
    {
        $this->assertCount(1, PaymentGateways::ids());
    }

    protected function resolveApplicationConfiguration($app)
    {
        parent::resolveApplicationConfiguration($app);

        config(['{:name_space_root|dotnotation:}.gateway.register' => false]);
    }
}
