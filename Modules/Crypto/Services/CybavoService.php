<?php

namespace Modules\Crypto\Services;

use Illuminate\Support\Facades\DB;
use Modules\Crypto\Enums\DepositOrderStatus;
use Modules\Crypto\Models\DepositOrder;
use Modules\Crypto\Repositories\DepositOrderRepository;
use Modules\Crypto\Repositories\PaymentMethodRepository;
use Modules\Point\Repositories\OrderNotificationRepository;

class CybavoService
{
    public $orderRepo;
    public $paymentMethodRepo;
    public $orderNotificationRepo;

    protected $requestUrl;

    public function __construct(
        DepositOrderRepository $orderRepo,
        PaymentMethodRepository $paymentMethodRepo,
        OrderNotificationRepository $orderNotificationRepo,
    ) {
        $this->orderRepo             = $orderRepo;
        $this->paymentMethodRepo     = $paymentMethodRepo;
        $this->orderNotificationRepo = $orderNotificationRepo;
        $this->requestUrl            = config('point.cybavo.api_server_url');
    }

    /**
     * params: array of withdrawal objects
     */
    public function getDepositPayload(
        string $cybavoOrderId,
        string $price,
        string $chainId,
    ) {
        $paymentMethod = $this->paymentMethodRepo->findByCryptoChainId($chainId);

        return [
            'currency'      => $paymentMethod->info['currency'],
            'token_address' => $paymentMethod->info['tokenAddress'],
            'amount'        => $price,
            'duration'      => (int) config('crypto.order_duration'),
            'order_id'      => $cybavoOrderId,
            'redirect_url'  => '',
        ];
    }

    public function createDepositOrder(
        string $cybavoOrderId,
        string $price,
        string $chainId,
    ) {
        $merchantId = config('crypto.wallet.deposit.merchant_id');
        $method     = 'POST';
        $uri        = "v1/merchant/{$merchantId}/order";
        $payload    = $this->getDepositPayload($cybavoOrderId, $price, $chainId);

        $query    = [];
        $postData = json_encode($payload);

        return $this->makeRequest($method, $uri, $query, $postData);
    }

    public function confirmDeposit(
        array $request
    ) {
        try {
            DB::beginTransaction();

            $order = $this->orderRepo->findByCybavoOrderId($request['order_id']);

            if (!$order) {
                throw new \Exception('deposit order not found');
            }

            $orderStatus = $request['state'];

            switch ($orderStatus) {
                case 0: // success
                    $order->status = DepositOrderStatus::PAID_SYSTEM->value;

                    break;
                case 1: // expired
                    $order->status = DepositOrderStatus::EXPIRED->value;

                    break;
                case 2: // less than
                    $order->status = DepositOrderStatus::ERROR_DEPOSIT_SHORTAGE->value;

                    break;
                case 3: // greater than
                    $order->status = DepositOrderStatus::ERROR_DEPOSIT_EXCESS->value;

                    break;
                case 4: // cancelled
                    $order->status = DepositOrderStatus::CANCELLED_SYSTEM->value;

                    break;
                default:
                    throw new \Exception('invalid callback status');
            }

            $order->tx_id        = $request['txid'];
            $order->from_address = $request['from_address'];
            $order->save();

            $this->createOrderNotification($order);

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();

            report($th);
        }

        return true;
    }

    private function genRandomString(int $length = 8)
    {
        $charset    = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $charsetLen = strlen($charset);
        $r          = '';
        for ($i = 0; $i < $length; $i++) {
            $r .= $charset[rand(0, $charsetLen - 1)];
        }

        return $r;
    }

    private function buildChecksum(
        array $params,
        string $secret,
        int $t,
        string $r,
        string $postData
    ) {
        array_push($params, "t={$t}", "r={$r}");

        if (!empty($postData)) {
            array_push($params, $postData);
        }
        sort($params);
        array_push($params, "secret={$secret}");

        return hash('sha256', implode('&', $params));
    }

    private function makeRequest(
        string $method,
        string $api,
        array $params = [],
        string $postData = ''
    ) {
        if (config('crypto.feature_toggle.cybavo') !== 'enabled') {
            throw new \Exception('Cybavo service is currently not available');
        }

        $r   = $this->genRandomString();
        $t   = time();
        $url = "{$this->requestUrl}/{$api}?t={$t}&r={$r}";

        if (!empty($params)) {
            $url .= '&' . implode('&', $params);
        }

        $ch     = curl_init();
        $header = [
            'X-API-CODE: ' . config('crypto.wallet.deposit.api_token'),
            'X-CHECKSUM: ' . $this->buildChecksum($params, config('crypto.wallet.deposit.api_secret'), $t, $r, $postData),
            'User-Agent: php',
        ];
        curl_setopt($ch, CURLOPT_URL, $url);

        if (!strcasecmp($method, 'POST') || !strcasecmp($method, 'DELETE')) {
            if (!strcasecmp($method, 'POST')) {
                curl_setopt($ch, CURLOPT_POST, true);
            } else {
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
            }

            if (!is_null($postData) && strlen($postData) > 0) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
                array_push($header, 'Content-Length: ' . strlen($postData));
            }
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result         = curl_exec($ch);
        $resp           = [];
        $resp['result'] = $result;
        $resp['status'] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return $resp;
    }

    public function createOrderNotification(
        DepositOrder $order,
    ) {
        $payload = [
            'userId'      => $order->user_id,
            'gameUserId'  => $order->game_user_id,
            'productCode' => $order->product_code,
            'chainId'     => $order->chain_id ?? null,
            'paymentType' => $order->payment_type,
            'price'       => $order->price,
            'createdTime' => $order->created_at,
            'updateTime'  => $order->updated_at,
        ];
        $this->orderNotificationRepo->create($order->client_id, $order->id, $payload);
    }
}
