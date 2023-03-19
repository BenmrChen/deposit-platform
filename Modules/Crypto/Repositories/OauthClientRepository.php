<?php

namespace Modules\Crypto\Repositories;

use Illuminate\Support\Arr;
use Modules\Passport\Models\Client;

class OauthClientRepository extends AbstractRepository
{
    public function __construct(Client $oauthClient)
    {
        parent::__construct($oauthClient);
    }

    public function getList($input)
    {
        $query = $this->model;

        if (Arr::get($input, 'forDeposit')) {
            $query = $query->depositEnabled();
        }

        if ($sort = Arr::get($input, 'sort')) {
            $sorting = $this->getSorting($sort);
            $query->orderBy($sorting['column'], $sorting['order']);
        } else {
            $query->orderBy('created_at', Arr::get($input, 'order', 'desc'));
        }

        return $query->paginate(
            Arr::get($input, 'pageSize', 20),
            ['*'],
            'page',
            Arr::get($input, 'page', 1)
        );
    }
}
