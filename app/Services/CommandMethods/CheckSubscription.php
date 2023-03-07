<?php


namespace App\Services\CommandMethods;


use App\Jobs\CheckRateLimitedRequests;
use App\Mocks\Google;
use App\Models\Subscription;
use Illuminate\Support\Facades\Http;

class CheckSubscription
{
    public static function initialize()
    {
        foreach (Subscription::where('status', '!=', 'canceled')->get() as $subscription) {
            Google::fake(['receipt' => $subscription->receipt]);
            $response = Http::get('www.google.com/expire-receipt');
            ($response->json()['status'] === 'rate-limiting-error') ?
                dispatch(new CheckRateLimitedRequests($subscription))->delay(now()->addMinutes(1)) :
                Subscription::where('id', $subscription->id)->update(['expire_date' => $response->json()['expire-date'], 'status' => $response->json()['status']]);
        }
    }
}
