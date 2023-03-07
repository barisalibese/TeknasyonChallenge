<?php


namespace App\Mocks;

use App\Models\Subscription;
use Illuminate\Support\Facades\Http;

class Google implements Mock
{

    public static function fake($data): void
    {
        $status = self::checkPurchasedReceiptIsValid($data['receipt']);
        Http::fake(['www.google.com/purchase' => Http::response([
                'status' => $status,
                'expire-date' => ($status === Subscription::STARTED || $status === Subscription::RENEWED) ?
                    now('Europe/Istanbul')->addDays(config('custom.day.value'))->format(config('custom.date-format')) :
                    now('Europe/Istanbul')->subDays(config('custom.day.value'))->format(config('custom.date-format'))
            ]),
                'www.google.com/expire-receipt' => Http::response([
                    'status' => (self::checkExpireReceipts($data['receipt'])),
                    'expire-date' => (self::checkExpireReceipts($data['receipt']) === Subscription::CANCELED) ?
                        now('Europe/Istanbul')->subDays(config('custom.day.value'))->format(config('custom.date-format')) :
                        null
                ]),
                'www.google.com/check-updated/renewed' => Http::response([
                    'status' => 'renewed',
                    'expire-date' => (self::checkExpireReceipts($data['receipt']) === Subscription::CANCELED) ?
                        now('Europe/Istanbul')->subDays(config('custom.day.value'))->format(config('custom.date-format')) :
                        null
                ]),

            ]
        );
    }

    private static function checkPurchasedReceiptIsValid($receipt): string
    {
        if ($receipt[-1] % 2 === 1 && Subscription::where('receipt', $receipt)->exists()) {
            return Subscription::RENEWED;
        } else if ($receipt[-1] % 2 === 1) {
            return Subscription::STARTED;
        }
        return Subscription::CANCELED;
    }

    private static function checkExpireReceipts($receipt): string
    {
        if (substr($receipt, -2) % 6 == 0) {
            return 'rate-limiting-error';
        } else if ($receipt[-1] % 2 != 1) {
            return Subscription::CANCELED;
        } else {
            return 'no-action';
        }
    }
}
