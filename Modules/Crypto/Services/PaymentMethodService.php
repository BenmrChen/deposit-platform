<?php

namespace Modules\Crypto\Services;

use Modules\Crypto\Repositories\PaymentMethodRepository;

class PaymentMethodService
{
    public $paymentMethodRepo;

    public function __construct(
        PaymentMethodRepository $paymentMethodRepo
    ) {
        $this->paymentMethodRepo = $paymentMethodRepo;
    }

    public function __call($function, $parameters)
    {
        if (method_exists($this->paymentMethodRepo, $function)) {
            return $this->paymentMethodRepo->{$function}(...$parameters);
        }

        throw new \Exception('call to undefined method');
    }
}
