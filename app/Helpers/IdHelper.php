<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;

/**
 * id helper function
 */
class IdHelper
{
    const LOGIC_SHARED_ID_DIGITS = 10;
    const SEQUENCE_NUMBER_DIGITS = 10;
    const MAX_SEQUENCE_NUMBER    = 1023;
    private string $logicSharedId;

    public function __construct()
    {
        $this->logicSharedId = $this->getLogicSharedId();
    }

    /**
     * 產生id
     */
    public function genId(): string
    {
        $firstBit       = '';
        $timestamp      = $this->getTimestamp();
        $sequenceNumber = $this->getSequenceNumber();

        return (string) bindec($firstBit . $timestamp . $this->logicSharedId . $sequenceNumber);
    }

    /**
     * logic shared id, 隨機分配給 hostname + pid 一個 id
     */
    private function getLogicSharedId(): string
    {
        $key = gethostname() . '-' . getmypid();

        if (!$logicSharedId = Cache::get($key)) {
            $logicSharedId = mt_rand(0, 1023);
            Cache::put($key, $logicSharedId, now()->addDays(3));
        }

        return str_pad(decbin($logicSharedId), self::LOGIC_SHARED_ID_DIGITS, '0', STR_PAD_LEFT);
    }

    /**
     * timestamp
     */
    private function getTimestamp(): string
    {
        $microtime = microtime();

        return decbin((int) (substr($microtime, 11, 10) . substr($microtime, 2, 3)));
    }

    /**
     * 利用 cache 機制建立每秒最大1023的seq no
     */
    private function getSequenceNumber(): string
    {
        $time  = time();
        $key   = $this->logicSharedId . '-' . $time;
        $seqNo = Cache::increment($key);

        // 超過最大值時等到下一秒
        if ($seqNo > self::MAX_SEQUENCE_NUMBER) {
            while (true) {
                $timeNow = time();

                if ($time != $timeNow) {
                    break;
                }
            }

            $key   = $this->logicSharedId . '-' . $timeNow;
            $seqNo = Cache::increment($key);
        }

        return str_pad(decbin((int) $seqNo), self::SEQUENCE_NUMBER_DIGITS, '0', STR_PAD_LEFT);
    }
}
