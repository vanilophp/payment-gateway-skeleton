<?php

declare(strict_types=1);

namespace {:name_space_root:}\Messages;

use Konekt\Enum\Enum;
use Vanilo\Payment\Contracts\PaymentResponse;
use Vanilo\Payment\Contracts\PaymentStatus;
use Vanilo\Payment\Models\PaymentStatusProxy;
use {:name_space_root:}\Models\XXXStatus;


class XXXPaymentResponse implements PaymentResponse
{
    private string $paymentId;

    private ?float $amountPaid;

    private XXXStatus $nativeStatus;

    private ?PaymentStatus $status = null;

    private string $message;

    private ?string $transactionId;

    public function __construct(
        string $paymentId,
        XXXStatus $nativeStatus,
        string $message,
        ?float $amountPaid = null,
        ?string $transactionId = null
    ) {
        // Arguments are just an example here, feel free, to modify
        $this->paymentId = $paymentId;
        $this->nativeStatus = $nativeStatus;
        $this->amountPaid = $amountPaid;
        $this->message = $message;
        $this->transactionId = $transactionId;
    }

    public function wasSuccessful(): bool
    {
        // implement it based on the gateway logic
    }

    public function getMessage(): string
    {
        // Just an example, feel free to implement a different logic
        return $this->message ?? $this->nativeStatus->label();
    }

    public function getTransactionId(): ?string
    {
        return $this->transactionId;
    }

    public function getAmountPaid(): ?float
    {
        return $this->amountPaid;
    }

    public function getPaymentId(): string
    {
        return $this->paymentId;
    }

    public function getStatus(): PaymentStatus
    {
        if (null === $this->status) {
            // Obtain the mapped status from the transaction
            // it usually takes the native status, message
            // or other data from the gateway callback
            // and applies mapping to Vanilo Status

            // Use the `PaymentStatusProxy` when creating values
            // in order to keep the status enum customizable

            // >>> **Example** for mapping status from the native status
            switch ($this->getNativeStatus()->value()) {
                case XXXStatus::CREATED:
                case XXXStatus::PENDING_OK:
                    $this->status = PaymentStatusProxy::PENDING();
                    break;
                case XXXStatus::AUTH_OK:
                    $this->status = PaymentStatusProxy::AUTHORIZED();
                    break;
                case XXXStatus::INVALID_DATA:
                case XXXStatus::FRAUD_DETECTED:
                    $this->status = PaymentStatusProxy::DECLINED();
                    break;
                case XXXStatus::CAPTURED:
                    $this->status = PaymentStatusProxy::PAID();
                    break;
                case XXXStatus::FRAUD_CHECK:
                    $this->status = PaymentStatusProxy::ON_HOLD();
                    break;
                default:
                    $this->status = PaymentStatusProxy::DECLINED();
            }
            // End of Example <<<
        }

        return $this->status;
    }

    public function getNativeStatus(): Enum
    {
        return $this->nativeStatus;
    }
}
