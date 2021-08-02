<?php

declare(strict_types=1);

namespace {:name_space_root:}\Messages;

use Illuminate\Support\Facades\View;
use Vanilo\Payment\Contracts\PaymentRequest;

class XXXPaymentRequest implements PaymentRequest
{
    private string $view = '{:payment_gateway_name|slug:}::_request';

    private string $approveUrl;

    public function __construct()
    {
        // accept arguments, initialize
    }

    public function getHtmlSnippet(array $options = []): ?string
    {
        return View::make(
            $this->view,
            [
                // Inject needed variables here
            ]
        )->render();
    }

    public function willRedirect(): bool
    {
        return true;
    }

    public function setView(string $view): self
    {
        $this->view = $view;

        return $this;
    }
}
