<?php

namespace Modules\Crypto\Services;

use Modules\Crypto\Repositories\DepositProductRepository;
use Modules\Crypto\Repositories\OauthClientRepository;

class ProductService
{
    public $oauthClientRepo;
    public $depositProductRepo;

    public function __construct(
        OauthClientRepository $oauthClientRepo,
        DepositProductRepository $depositProductRepo
    ) {
        $this->oauthClientRepo    = $oauthClientRepo;
        $this->depositProductRepo = $depositProductRepo;
    }

    public function __call($function, $parameters)
    {
        if (method_exists($this->depositProductRepo, $function)) {
            return $this->depositProductRepo->{$function}(...$parameters);
        }

        throw new \Exception('call to undefined method');
    }

    public function getClientList(
        array $input
    ) {
        return $this->oauthClientRepo->getList($input);
    }
}
