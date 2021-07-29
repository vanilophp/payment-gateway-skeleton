<?php

declare(strict_types=1);

namespace {:name_space_root:};

use Illuminate\Http\Request;
use Vanilo\Contracts\Address;
use Vanilo\Payment\Contracts\Payment;
use Vanilo\Payment\Contracts\PaymentGateway;
use Vanilo\Payment\Contracts\PaymentRequest;
use Vanilo\Payment\Contracts\PaymentResponse;

class XXXPaymentGateway implements PaymentGateway
{
    public const DEFAULT_ID = '{:payment_gateway_name|slug:}';

    public static function getName(): string
    {
        return '{:payment_gateway_name:}';
    }

    public function createPaymentRequest(Payment $payment, Address $shippingAddress = null, array $options = []): PaymentRequest
    {
        // @todo implement
    }

    public function processPaymentResponse(Request $request, array $options = []): PaymentResponse
    {
        // @todo implement
    }

    public function isOffline(): bool
    {
        return false;
    }
}
