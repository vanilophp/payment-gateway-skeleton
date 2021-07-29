<?php

declare(strict_types=1);

use {:name_space_root:}\XXXPaymentGateway;

return [
    'gateway' => [
        'register' => true,
        'id' => XXXPaymentGateway::DEFAULT_ID
    ],
    'bind' => true,
    'xxx' => env('{:payment_gateway_name|slug|toupper:}_XXX'),
];
