<?php

declare(strict_types=1);

namespace {:name_space_root:}\Providers;

use Konekt\Concord\BaseModuleServiceProvider;
use Vanilo\Payment\PaymentGateways;
use {:name_space_root:}\XXXPaymentGateway;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    public function boot()
    {
        parent::boot();

        if ($this->config('gateway.register', true)) {
            PaymentGateways::register(
                $this->config('gateway.id', XXXPaymentGateway::DEFAULT_ID),
                XXXPaymentGateway::class
            );
        }

        if ($this->config('bind', true)) {
            $this->app->bind(XXXPaymentGateway::class, function ($app) {
                return new XXXPaymentGateway(
                    $this->config('xxx') // @todo replace with real
                );
            });
        }

        $this->publishes([
            $this->getBasePath() . '/' . $this->concord->getConvention()->viewsFolder() =>
            resource_path('views/vendor/{:payment_gateway_name|slug:}'),
            'vanilo-{:payment_gateway_name|slug:}'
        ]);
    }
}
