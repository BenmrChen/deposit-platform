<?php

namespace Modules\Crypto\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Modules\Crypto\Services\ProductService;

class AddProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crypto:add-product
            {clientId}
            {productCode}
            {paymentType : CRYPTO/3RD_PARTY }
            {price}
            {startedAt : 2023-01-01 00:00:00}
            {endedAt : 2023-12-31 23:59:59}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'add product';

    /**
     * Services
     */
    protected $productService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        ProductService $productService
    ) {
        parent::__construct();

        $this->productService = $productService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $clientId    = $this->argument('clientId');
        $productCode = $this->argument('productCode');
        $paymentType = $this->argument('paymentType');
        $price       = $this->argument('price');
        $startedAt   = $this->argument('startedAt');
        $endedAt     = $this->argument('endedAt');

        $rules = [
            'clientId'    => 'required|uuid',
            'productCode' => 'required|string',
            'paymentType' => 'required|in:CRYPTO,3RD_PARTY',
            'price'       => 'required|string',
            'startedAt'   => 'required|date_format:Y-m-d H:i:s',
            'endedAt'     => 'required|date_format:Y-m-d H:i:s',
        ];

        $validator = Validator::make($this->arguments(), $rules);

        if ($validator->fails()) {
            $errors = implode(',', array_keys($validator->errors()->toArray()));
            $this->error('Invalid argument(s): ' . $errors);

            return Command::FAILURE;
        }

        try {
            $this->productService->create($clientId, $productCode, $paymentType, $price, $startedAt, $endedAt);

            $this->info('Successfully added product...');
        } catch (\Throwable $th) {
            $this->error('Failed to add product...');
            $this->error('Error: ' . $th->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
