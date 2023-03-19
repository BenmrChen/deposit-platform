<?php

namespace Modules\Crypto\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Modules\Crypto\Enums\DepositOrderStatus;
use Modules\Crypto\Services\CybavoService;
use Modules\Crypto\Services\OrderService;

class ModifyOrderStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crypto:modify-order-status
            {orderId}
            {action : SET-CANCELLED / SET-PAID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'modify order as cancelled or paid';

    /**
     * Services
     */
    protected $orderService;
    protected $cybavoService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(
        OrderService $orderService,
        CybavoService $cybavoService,
    ) {
        parent::__construct();

        $this->orderService  = $orderService;
        $this->cybavoService = $cybavoService;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $orderId = $this->argument('orderId');
        $action  = $this->argument('action');

        $rules = [
            'orderId' => 'required|string',
            'action'  => 'required|string|in:SET-PAID,SET-CANCELLED',
        ];

        $validator = Validator::make($this->arguments(), $rules);

        if ($validator->fails()) {
            $errors = implode(',', array_keys($validator->errors()->toArray()));
            $this->error('Invalid argument(s): ' . $errors);

            return Command::FAILURE;
        }

        $order = $this->orderService->findById($orderId);

        if (!$order) {
            $this->error('Cannot find deposit order...');

            return Command::FAILURE;
        }

        try {
            if ($action == 'SET-PAID') {
                if ($order->status != DepositOrderStatus::ERROR_DEPOSIT_EXCESS) {
                    $this->error('Cannot set status paid as original status is not ERROR_DEPOSIT_EXCESS');

                    return Command::FAILURE;
                }

                $order->status = DepositOrderStatus::PAID_MANUALLY;
                $order->save();

                $this->cybavoService->createOrderNotification($order);
            } elseif ($action == 'SET-CANCELLED') {
                if (!in_array($order->status, [DepositOrderStatus::ERROR_DEPOSIT_EXCESS, DepositOrderStatus::ERROR_DEPOSIT_SHORTAGE])) {
                    $this->error('Cannot set status cancelled as original status is not ERROR_DEPOSIT_EXCESS or ERROR_DEPOSIT_SHORTAGE');

                    return Command::FAILURE;
                }

                $order->status = DepositOrderStatus::CANCELLED_MANUALLY->value;
                $order->save();
            }
        } catch (\Throwable $th) {
            $this->error('Failed to modify order status...');
            $this->error('Error: ' . $th->getMessage());

            return Command::FAILURE;
        }

        $this->info('Successfully modified order status...');

        return Command::SUCCESS;
    }
}
