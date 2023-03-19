<?php

namespace Modules\Crypto\Http\Middleware;

use Closure;
use Modules\Point\Repositories\CybavoWalletRepository;

class VerifyCybavoDepositChecksum
{
    protected $cybavoWallet;

    public function __construct(
        CybavoWalletRepository $cybavoWallet
    ) {
        $this->cybavoWallet = $cybavoWallet;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $postData = $request->getContent();

        $headerChecksum = $request->server('HTTP_X_CHECKSUM');
        $payload        = $postData . config('crypto.wallet.deposit.api_secret');
        $checksum       = $this->base64UrlEncode(hash('sha256', $payload, true));

        if (strcmp($headerChecksum, $checksum) !== 0) {
            throw new \Exception('Invalid callback');
        }

        return $next($request);
    }

    private function base64UrlEncode($data)
    {
        return strtr(base64_encode($data), '+/', '-_');
    }
}
