<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Arr;

class PointException extends Exception
{
    protected array $detail = [];
    protected string $msg   = 'An error occurred';

    public function __construct(string $code, mixed $detail = null)
    {
        $this->detail = $detail ? Arr::wrap($detail) : [];
        $this->setCodeAndMessage($code);
    }

    public function setCodeAndMessage(string $code): void
    {
        $status = config('status_code');

        $this->code = $code;
        $this->msg  = $status[$this->code]['message'];
    }

    public function getDetail(?string $key = null): mixed
    {
        if ($key) {
            return Arr::get($this->detail, $key);
        }

        return $this->detail;
    }

    public function render(): JsonResponse
    {
        $code       = $this->code;
        $statusCode = 200;
        $message    = $this->msg;
        $detail     = $this->getDetail();
        $data       = ['detail' => $detail];

        $status = compact('code', 'message');

        return response()->json(compact('status', 'data'), $statusCode);
    }
}
