<?php

namespace Modules\Crypto\Services;

use Modules\Crypto\Repositories\DepositOrderRepository;
use Modules\Point\Repositories\ClientRepository;
use Modules\Point\Repositories\OrderNotificationRepository;

class OrderService
{
    public $depositOrderRepository;
    public $notificationRepo;
    public $clientRepo;

    public function __construct(
        DepositOrderRepository $depositOrderRepository,
        OrderNotificationRepository $notificationRepo,
        ClientRepository $clientRepo
    ) {
        $this->depositOrderRepository = $depositOrderRepository;
        $this->notificationRepo       = $notificationRepo;
        $this->clientRepo             = $clientRepo;
    }

    public function __call($function, $parameters)
    {
        if (method_exists($this->depositOrderRepository, $function)) {
            return $this->depositOrderRepository->{$function}(...$parameters);
        }

        throw new \Exception('call to undefined method');
    }
}
