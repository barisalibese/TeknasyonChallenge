<?php

namespace App\Jobs;

use App\Mocks\Google;
use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class CheckRateLimitedRequests implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private object $subscription;

    /**
     * The number of times the job may be attempted.
     *
     * @var int
     */
    public int $tries = 5;

    /**
     * Create a new job instance.
     * @param $subscription
     */
    public function __construct($subscription)
    {
        $this->subscription = $subscription;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Google::fake(['receipt' => $this->subscription->receipt]);
        $response = Http::get('www.google.com/expire-receipt');
        if ($this->subscription->status != Subscription::CANCELED && $response->json()['status'] === Subscription::CANCELED) {
            Subscription::where('id', $this->subscription->id)->update(['expire_date' => $response->json()['expire-date'], 'status' => $response->json()['status']]);
        } else if ($response->json()['status'] == 'rate-limiting-error') {
            info('asdjgsaldg');
            $this->release(30);
        }
    }
}
